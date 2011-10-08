## -*- Makefile -*-
##
## 用户： hovey
## 时间： 2011-1-6 21:17:53
## Makefile created by Oracle Solaris Studio.
##
## 此文件是自动生成的。
##


#### 由所有生成目标共享的编译器和工具定义 #####
CC = gcc
BASICOPTS = -g
CFLAGS = $(BASICOPTS)
SYSLIBS_judge = -lmysqlclient


# 定义目标目录。
TARGETDIR_judge=bin


all: $(TARGETDIR_judge)/judge

## 目标： judge
CPPFLAGS_judge = \
	-Iinclude
OBJS_judge =  \
	$(TARGETDIR_judge)/execute.o \
	$(TARGETDIR_judge)/config.o \
	$(TARGETDIR_judge)/judge.o
USERLIBS_judge = $(SYSLIBS_judge) 
DEPLIBS_judge =  
LDLIBS_judge = $(USERLIBS_judge)


# 链接或归档
$(TARGETDIR_judge)/judge: $(TARGETDIR_judge) $(OBJS_judge) $(DEPLIBS_judge)
	$(LINK.c) $(CFLAGS_judge) $(CPPFLAGS_judge) -o $@ $(OBJS_judge) $(LDLIBS_judge)


# 将源文件编译为 .o 文件
$(TARGETDIR_judge)/execute.o: $(TARGETDIR_judge) execute.c
	$(COMPILE.c) $(CFLAGS_judge) $(CPPFLAGS_judge) -o $@ execute.c

$(TARGETDIR_judge)/config.o: $(TARGETDIR_judge) config.c
	$(COMPILE.c) $(CFLAGS_judge) $(CPPFLAGS_judge) -o $@ config.c

$(TARGETDIR_judge)/judge.o: $(TARGETDIR_judge) judge.c
	$(COMPILE.c) $(CFLAGS_judge) $(CPPFLAGS_judge) -o $@ judge.c



#### 清理目标将会删除所有生成的文件 ####
clean:
	rm -f \
		$(TARGETDIR_judge)/judge \
		$(TARGETDIR_judge)/execute.o \
		$(TARGETDIR_judge)/config.o \
		$(TARGETDIR_judge)/judge.o
	rm -f -r $(TARGETDIR_judge)


install:
	cp init/judge.conf /tmp/
	nano /tmp/judge.conf
	sudo cp /tmp/judge.conf /etc/init/judge1.conf
	sudo cp /tmp/judge.conf /etc/init/judge2.conf
	sudo cp /tmp/judge.conf /etc/init/judge3.conf
	sudo cp /tmp/judge.conf /etc/init/judge4.conf

config:
	cp judge.conf.sample judge.conf
	nano judge.conf


# 创建目标目录（如果需要）
$(TARGETDIR_judge):
	mkdir -p $(TARGETDIR_judge)


# 启用依赖关系检查
.KEEP_STATE:
.KEEP_STATE_FILE:.make.state.GNU-i386-Linux

