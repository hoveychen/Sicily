
#define __EXECUTE_C__

#include<sys/time.h>
#include<sys/types.h>
#include<sys/wait.h>
#include<sys/resource.h>
#include<sys/ptrace.h>
#include<sys/stat.h>
#include<sys/user.h>
#include<signal.h>
#include<unistd.h>
#include<stdio.h>
#include<string.h>

#include"include/execute.h"

/*
 * 6 possible status
 * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 0. OK     :  success
 * -1. RTE    :  runtime error
 * -2. TLE    :  time limit exceed
 * -3. MLE    :  memory limit exceed
 * -4. OLE    :  output limit exceed
 * -5. RF     :  restrict function
 */
int execute(const char *input_file_name, const char *output_file_name, const char *execute_file_name, int time_limit,
            int memory_limit, int output_limit, int *run_memory, double *cputime_ptr)
{
    int          pid, timer_pid, ret_pid;
    int          sig;
    int          mem_sum = 0,
                 maxmem  = 0;
    unsigned int cur_sum, start, end;
    int          status;
    int          exitcode = 1;
    char         procmaps[256];
    char         buffer[256];
    int          syscall_cnt   = 0;
    int          syscall_enter = 1;
    double       cputime       = -1;
    int          syscall_used[512];
    int          syscall_number;
    int          inode;
    struct rusage ru;
    struct rlimit limit;
    struct user_regs_struct regs;
    FILE*        maps;

    // hack: triple the time limitation of java
    if (isJava)
    {
        time_limit *= 3;
    }

    pid = fork();

    if (pid < 0)
    {
        perror("PANIC: fork ERROR ");
        _exit(1);
    }

    // ========================== execute program ==================================
    if (pid == 0)
    {
        printf("[child]: ready.\n");

        if (freopen(input_file_name, "r", stdin) == NULL)
        {
            fprintf(stderr, "[child]: ERROR open data file %s", input_file_name);
            perror(" ");
            _exit(1);
        }

        if (freopen(output_file_name, "w", stdout) == NULL)
        {
            fprintf(stderr, "[child]: ERROR open output file %s", output_file_name);
            perror(" ");
            _exit(1);
        }

        // put limits on myself
        limit.rlim_cur = time_limit;
        limit.rlim_max = time_limit + 1;

        if (setrlimit(RLIMIT_CPU, &limit))
        {
            perror("[child]: setting time limit: ");
            _exit(1);
        }

        limit.rlim_cur = output_limit - 1;
        limit.rlim_max = output_limit;

        if (setrlimit(RLIMIT_FSIZE, &limit))
        {
            perror("[child]: setting output limit: ");
            _exit(1);
        }

        limit.rlim_cur = 0;
        limit.rlim_max = 0;

        if (setrlimit(RLIMIT_CORE, &limit))
        {
            perror("[child]: setting core limit: ");
            _exit(1);
        }

        if (!isJava)
        {
            // ptrace can not be used in java
            ptrace(PTRACE_TRACEME, 0, 0, 0);    // trace !
        }

        execve(execute_file_name, 0, 0);
        fprintf(stderr, "[child]: ERROR executing %s", execute_file_name);
        perror(" ");
        _exit(1);
    }

    // ==============================  timer  ======================================
    fprintf(stderr, "[Judge]: child process id:%d\n", pid);
    fprintf(stderr, "[Judge]: creating it's brother as timer\n");

    timer_pid = fork();

    if (timer_pid < 0)
    {
        perror("[Judge]: ERROR creating timer");
        kill(pid, SIGKILL);
        _exit(1);
    }
    else if (timer_pid == 0)
    {
        // ==== timer
        fprintf(stderr, "[timer]: ready!\n");
        sleep(3 * time_limit);    // should be enough time....
        fprintf(stderr, "[timer]: time up! exiting!\n");
        _exit(1);
    }

    // ==============================  judge  ======================================
    memset(syscall_used, 0, sizeof(syscall_used));

    // initialize allowable system calls
    syscall_used[1]  = 1;    // exit
    syscall_used[3]  = 1;    // read
    syscall_used[4]  = 1;    // write
    syscall_used[5]  = 1;    // open
    syscall_used[6]  = 1;    // close
    syscall_used[45] = 1;    // brk
    syscall_used[54] = 1;    // ioctl

    // syscall_used[ 67] = 1;// sigaction
    syscall_used[90]  = 1;    // mmap
    syscall_used[91]  = 1;    // munmap
    syscall_used[122] = 1;    // uname

    // syscall_used[186] = 1;// sigaltsatck
    syscall_used[192] = 1;    // mmap2
    syscall_used[197] = 1;    // fstat64
    syscall_used[199] = 1;    // getuid32    Get user identity
    syscall_used[200] = 1;    // getegid32   Get group identity
    syscall_used[201] = 1;    // geteuid32   Get user identity
    syscall_used[202] = 1;    // getgid32    Get group identity
    syscall_used[221] = 1;    // getdents64  Get directory entrys
    syscall_used[252] = 1;    // exit group
    syscall_used[140] = 1;    // llseek
    syscall_used[191] = 1;
    syscall_used[174] = 1;
    syscall_used[85]  = 1;
    syscall_used[243] = 1;
    syscall_used[146] = 1;
    syscall_used[145] = 1;

    // syscall_used[175] = 1;
    // syscall_used[20] = 1;
    sprintf(procmaps, "/proc/%d/maps", pid);
    fprintf(stderr, "[Judge]: maps: %s\n", procmaps);
    wait3(&status, 0, &ru);    // wait for pid STOP after execve(), hopefully it won't be the timer
    fprintf(stderr, "[JUDGE]: status_code = %d!\n", WEXITSTATUS(status));

    if (WIFEXITED(status))
    {
        fprintf(stderr, "[Judge]:  program exit before tracing\n");
        kill(timer_pid, SIGKILL);

        // ugly quickfix ...
        int exs = WEXITSTATUS(status);

        switch (exs)
        {
            case 0:
                fprintf(stderr, "[Judge]:  %d exited normally\n", pid);

                return 0;

            case 1:
                *cputime_ptr = time_limit;

                fprintf(stderr, "[Judge]:  timer exited\n");

                return -2;

            default:
                fprintf(stderr, "[Judge]:  unexpected signal caught \n");

                return -1;
        }
    }

    if (!isJava)
    {
        // ptrace can not be used in java
        ptrace(PTRACE_SYSCALL, pid, 0, 0);    // restart
    }

    while ((ret_pid = wait3(&status, 0, &ru)) > 0)
    {
        if (ret_pid == timer_pid)
        {                             // time limit
            kill(pid, SIGKILL);

            cputime  = time_limit;    // set time to TLE
            exitcode = -2;

            fprintf(stderr, "[Judge]:  timer exited\n");

            continue;
        }

        // pid STOPPED
        if (WIFEXITED(status))
        {
            // pid exited normally
            fprintf(stderr, "[Judge]:  %d exited normally; status = %d \n", pid, WEXITSTATUS(status));

            exitcode = 0;             // =======success

            break;
        }

        if (WIFSIGNALED(status))
        {
            sig = WTERMSIG(status);

            fprintf(stderr, "[Judge]:  deadly signal caught %d(%s) by %d\n", sig, signal_names[sig], pid);

            break;
        }

        if (WIFSTOPPED(status))
        {
            sig = WSTOPSIG(status);

            if (sig != SIGTRAP)
            {
                fprintf(stderr, "[Judge]:  unexpected signal caught %d(%s) killing %d\n", sig, signal_names[sig], pid);
                ptrace(PTRACE_KILL, pid, 0, 0);

                switch (sig)
                {
                    case SIGXCPU:
                        exitcode = -2;

                        break;

                    case SIGXFSZ:
                        exitcode = -4;

                        break;

                    case SIGSEGV:
                    default:
                        exitcode = -1;

                        break;
                }

                continue;
            }

            // SIGTRAP
            ptrace(PTRACE_GETREGS, pid, 0, &regs);

            syscall_number = regs.orig_eax;

            if (syscall_enter)
            {
                syscall_cnt++;

                // fprintf(stderr, "<\n");//entering syscall\n");
                // fprintf(stderr, "[trace]: syscall[%d]: %s\n",
                // syscall_number, syscall_names[syscall_number]);
                if (syscall_number == 45)
                {                     // brk
                    fprintf(stderr, "[trace]: brk (ebx = 0x%08lx) %lu\n", regs.ebx, regs.ebx);
                }

                //
                // check before execute syscall
                // modify syscall for restricted function call
                if (!syscall_used[syscall_number] &&!isJava)
                {
                    // oh no~ kill it!!!!!
                    fprintf(stderr, "[trace]: syscall[%d]: %s : Restrict function!\n", syscall_number,
                            syscall_names[syscall_number]);
                    fprintf(stderr, "[trace]: killing process %d  .\\/.\n", pid);

                    exitcode = -5;

                    ptrace(PTRACE_KILL, pid, 0, 0);    //

                    continue;
                }
            }
            else
            {
                if ((maps = fopen(procmaps, "r")) != NULL)
                {
                    // =================================check mem<<<
                    cur_sum = 0;

                    while (fgets(buffer, 256, maps))
                    {
                        sscanf(buffer, "%x-%x %*s %*s %*s %d", &start, &end, &inode);

                        if (inode == 0)
                        {
                            cur_sum += end - start;    // possiblly all data/stack segments
                        }
                    }

                    fclose(maps);

                    cur_sum >>= 10;                    // bytes -> kb

                    if (cur_sum != mem_sum)
                    {
                        fprintf(stderr, "[Judge]: proc %d memory usage: %dk\n", pid, cur_sum);

                        mem_sum = cur_sum;

                        if (maxmem < mem_sum)
                        {
                            maxmem = mem_sum;
                        }

                        if (cur_sum > memory_limit)
                        {
                            fprintf(stderr, "[Judge]:  Memory Limit Exceed\n");
                            fprintf(stderr, "[Judge]:  killing process %d .\\/.\n", pid);
                            kill(pid, SIGKILL);

                            exitcode = -3;             // ====MLE

                            continue;
                        }
                    }
                }

                // fprintf(stderr, ">\n");//leaving syscall\n");
            }

            syscall_enter = !syscall_enter;

            ptrace(PTRACE_SYSCALL, pid, 0, 0);
        }
    }

    fprintf(stderr, "[Judge]:  maximum memory used by %s: %dk\n", execute_file_name, maxmem);
    fprintf(stderr, "[Judge]:  utime sec %d, usec %06d\n", (int) ru.ru_utime.tv_sec, (int) ru.ru_utime.tv_usec);
    fprintf(stderr, "[Judge]:  stime sec %d, usec %06d\n", (int) ru.ru_stime.tv_sec, (int) ru.ru_stime.tv_usec);
    fprintf(stderr, "[Judge]:  mem usage %d \n", (int) ru.ru_maxrss);

    if (cputime < 0)
    {
        cputime = ru.ru_utime.tv_sec + ru.ru_utime.tv_usec * 1e-6 + ru.ru_stime.tv_sec + ru.ru_stime.tv_usec * 1e-6;
    }

    *cputime_ptr = cputime;
    *run_memory  = maxmem;

    fprintf(stderr, "[Judge]: cputime used %.4f\n", cputime);

    // kill(timer_pid, SIGINT);
    kill(timer_pid, SIGKILL);
    wait(&status);
    wait(&status);
    fprintf(stderr, "[Judge]: exiting   Total syscall %d\n", syscall_cnt);

    return exitcode;
}
