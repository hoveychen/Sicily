int isJava;

int execute(const char *input_file_name, const char *output_file_name, const char *execute_file_name, int time_limit,
            int memory_limit, int output_limit, int *run_memory, double *cputime_ptr);

#ifdef __EXECUTE_C__

char*(signal_names[32]) =
{
    // Signal                Value   Action  Comment
    // "-------------------------------------------------------------------------",//
    "(null)", "SIGHUP",    // 1       A       Hangupdetectedoncontrollingterminal or death of controllingprocess
    "SIGINT",              // 2       A       Interruptfromkeyboard
    "SIGQUIT",             // 3       A       Quitfromkeyboard
    "SIGILL",              // 4       A       IllegalInstruction
    "SIGTRAP",             // 5       CG      Trace/breakpointtrap
    "SIGABRT",             // 6       C       Abortsignalfromabort(3)

    // "SIGIOT    ",//       6       CG      IOTtrap.Asynonymfor
    "SIGBUS",       // 7       AG      Buserror
    "SIGFPE",       // 8       C       Floatingpointexception
    "SIGKILL",      // 9       AEF     Killsignal
    "SIGUSR1",      // 10      A       User-definedsignal1
    "SIGSEGV",      // 11      C       Invalidmemoryreference
    "SIGUSR2",      // 12      A       User-definedsignal2
    "SIGPIPE",      // 13      A       Brokenpipe:writetopipe
    "SIGALRM",      // 14      A       Timersignalfromalarm(2)
    "SIGTERM",      // 15      A       Terminationsignal
    "SIGSTKFLT",    // 16      AG      Stackfaultoncoprocessor
    "SIGCHLD",      // 17      B       Childstoppedorterminated
    "SIGCONT",      // 18      Continue        ifstopped
    "SIGSTOP",      // 19      DEF     Stopprocess
    "SIGTSTP",      // 20      D       Stoptypedattty
    "SIGTTIN",      // 21      D       ttyinputforbackgroundprocess
    "SIGTTOU",      // 22      D       ttyoutputforbackgroundprocess
    "SIGURG",       // 23      BG      Urgentconditiononsocket(4.2
    "SIGXCPU",      // 24      AG      CPUtimelimitexceeded(4.2
    "SIGXFSZ",      // 25      AG      Filesizelimitexceeded(4.2
    "SIGVTALRM",    // 26      AG      Virtualalarmclock(4.2BSD)
    "SIGPROF",      // 27      AG      Profilealarmclock
    "SIGWINCH",     // 28      BG      Windowresizesignal(4.3BSD,
    "SIGIO",        // 29      AG      I/Onowpossible(4.2BSD)
    "SIGPWR",       // 30      AG      Powerfailure(SystemV)
    "SIGUNUSED"     // 31      AG      Unusedsignal
};

/*
 *  * syscall names for i386 under 2.5.51, taken from <asm/unistd.h>
 */
char*(syscall_names[256]) =
{
    "null",                      // 0
    "exit",                      // 1
    "fork",                      // 2
    "read",                      // 3
    "write",                     // 4
    "open",                      // 5
    "close",                     // 6
    "waitpid",                   // 7
    "creat",                     // 8
    "link",                      // 9
    "unlink",                    // 10
    "execve",                    // 11
    "chdir",                     // 12
    "time",                      // 13
    "mknod",                     // 14
    "chmod",                     // 15
    "lchown",                    // 16
    "break",                     // 17
    "oldstat",                   // 18
    "lseek",                     // 19
    "getpid",                    // 20
    "mount",                     // 21
    "umount",                    // 22
    "setuid",                    // 23
    "getuid",                    // 24
    "stime",                     // 25
    "ptrace",                    // 26
    "alarm",                     // 27
    "oldfstat",                  // 28
    "pause",                     // 29
    "utime",                     // 30
    "stty",                      // 31
    "gtty",                      // 32
    "access",                    // 33
    "nice",                      // 34
    "ftime",                     // 35
    "sync",                      // 36
    "kill",                      // 37
    "rename",                    // 38
    "mkdir",                     // 39
    "rmdir",                     // 40
    "dup",                       // 41
    "pipe",                      // 42
    "times",                     // 43
    "prof",                      // 44
    "brk",                       // 45
    "setgid",                    // 46
    "getgid",                    // 47
    "signal",                    // 48
    "geteuid",                   // 49
    "getegid",                   // 50
    "acct",                      // 51
    "umount2",                   // 52
    "lock",                      // 53
    "ioctl",                     // 54
    "fcntl",                     // 55
    "mpx",                       // 56
    "setpgid",                   // 57
    "ulimit",                    // 58
    "oldolduname",               // 59
    "umask",                     // 60
    "chroot",                    // 61
    "ustat",                     // 62
    "dup2",                      // 63
    "getppid",                   // 64
    "getpgrp",                   // 65
    "setsid",                    // 66
    "sigaction",                 // 67
    "sgetmask",                  // 68
    "ssetmask",                  // 69
    "setreuid",                  // 70
    "setregid",                  // 71
    "sigsuspend",                // 72
    "sigpending",                // 73
    "sethostname",               // 74
    "setrlimit",                 // 75
    "getrlimit",                 // 76
    "getrusage",                 // 77
    "gettimeofday",              // 78
    "settimeofday",              // 79
    "getgroups",                 // 80
    "setgroups",                 // 81
    "select",                    // 82
    "symlink",                   // 83
    "oldlstat",                  // 84
    "readlink",                  // 85
    "uselib",                    // 86
    "swapon",                    // 87
    "reboot",                    // 88
    "readdir",                   // 89
    "mmap",                      // 90
    "munmap",                    // 91
    "truncate",                  // 92
    "ftruncate",                 // 93
    "fchmod",                    // 94
    "fchown",                    // 95
    "getpriority",               // 96
    "setpriority",               // 97
    "profil",                    // 98
    "statfs",                    // 99
    "fstatfs",                   // 100
    "ioperm",                    // 101
    "socketcall",                // 102
    "syslog",                    // 103
    "setitimer",                 // 104
    "getitimer",                 // 105
    "stat",                      // 106
    "lstat",                     // 107
    "fstat",                     // 108
    "olduname",                  // 109
    "iopl",                      // 110
    "vhangup",                   // 111
    "idle",                      // 112
    "vm86old",                   // 113
    "wait4",                     // 114
    "swapoff",                   // 115
    "sysinfo",                   // 116
    "ipc",                       // 117
    "fsync",                     // 118
    "sigreturn",                 // 119
    "clone",                     // 120
    "setdomainname",             // 121
    "uname",                     // 122
    "modify_ldt",                // 123
    "adjtimex",                  // 124
    "mprotect",                  // 125
    "sigprocmask",               // 126
    "create_module",             // 127
    "init_module",               // 128
    "delete_module",             // 129
    "get_kernel_syms",           // 130
    "quotactl",                  // 131
    "getpgid",                   // 132
    "fchdir",                    // 133
    "bdflush",                   // 134
    "sysfs",                     // 135
    "personality",               // 136
    "afs_syscall",               // 137
    "setfsuid",                  // 138
    "setfsgid",                  // 139
    "_llseek",                   // 140
    "getdents",                  // 141
    "_newselect",                // 142
    "flock",                     // 143
    "msync",                     // 144
    "readv",                     // 145
    "writev",                    // 146
    "getsid",                    // 147
    "fdatasync",                 // 148
    "_sysctl",                   // 149
    "mlock",                     // 150
    "munlock",                   // 151
    "mlockall",                  // 152
    "munlockall",                // 153
    "sched_setparam",            // 154
    "sched_getparam",            // 155
    "sched_setscheduler",        // 156
    "sched_getscheduler",        // 157
    "sched_yield",               // 158
    "sched_get_priority_max",    // 159
    "sched_get_priority_min",    // 160
    "sched_rr_get_interval",     // 161
    "nanosleep",                 // 162
    "mremap",                    // 163
    "setresuid",                 // 164
    "getresuid",                 // 165
    "vm86",                      // 166
    "query_module",              // 167
    "poll",                      // 168
    "nfsservctl",                // 169
    "setresgid",                 // 170
    "getresgid",                 // 171
    "prctl",                     // 172
    "rt_sigreturn",              // 173
    "rt_sigaction",              // 174
    "rt_sigprocmask",            // 175
    "rt_sigpending",             // 176
    "rt_sigtimedwait",           // 177
    "rt_sigqueueinfo",           // 178
    "rt_sigsuspend",             // 179
    "pread",                     // 180
    "pwrite",                    // 181
    "chown",                     // 182
    "getcwd",                    // 183
    "capget",                    // 184
    "capset",                    // 185
    "sigaltstack",               // 186
    "sendfile",                  // 187
    "getpmsg",                   // 188
    "putpmsg",                   // 189
    "vfork",                     // 190
    "ugetrlimit",                // 191
    "mmap2",                     // 192
    "truncate64",                // 193
    "ftruncate64",               // 194
    "stat64",                    // 195
    "lstat64",                   // 196
    "fstat64",                   // 197
    "lchown32",                  // 198
    "getuid32",                  // 199
    "getgid32",                  // 200
    "geteuid32",                 // 201
    "getegid32",                 // 202
    "setreuid32",                // 203
    "setregid32",                // 204
    "getgroups32",               // 205
    "setgroups32",               // 206
    "fchown32",                  // 207
    "setresuid32",               // 208
    "getresuid32",               // 209
    "setresgid32",               // 210
    "getresgid32",               // 211
    "chown32",                   // 212
    "setuid32",                  // 213
    "setgid32",                  // 214
    "setfsuid32",                // 215
    "setfsgid32",                // 216
    "pivot_root",                // 217
    "mincore",                   // 218
    "madvise",                   // 219
    "madvise1",                  // 220
    "getdents64",                // 221
    "fcntl64",                   // 222
    0,                           // 223
    "security",                  // 224
    "gettid",                    // 225
    "readahead",                 // 226
    "setxattr",                  // 227
    "lsetxattr",                 // 228
    "fsetxattr",                 // 229
    "getxattr",                  // 230
    "lgetxattr",                 // 231
    "fgetxattr",                 // 232
    "listxattr",                 // 233
    "llistxattr",                // 234
    "flistxattr",                // 235
    "removexattr",               // 236
    "lremovexattr",              // 237
    "fremovexattr",              // 238
    "tkill",                     // 239
    "sendfile64",                // 240
    "futex",                     // 241
    "sched_setaffinity",         // 242
    "sched_getaffinity",         // 243
    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
};

#endif

