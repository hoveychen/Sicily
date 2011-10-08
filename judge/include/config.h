/* -*- mode: C; indent-tabs-mode: t; tab-width: 4; c-basic-offset: 4 -*- */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <stdarg.h>
#ifdef FREEBSD
#include <mysql.h>
#include <math.h>
#else
#include <mysql/mysql.h>
#endif
#include <sys/types.h>
#include <sys/stat.h>
#include <sys/wait.h>
#include <fcntl.h>
#include <time.h>

extern char HOST[];
extern char USER[];
extern char PASSWD[];
extern char DATABASE[];
extern char DATA_PREFIX[];
extern char SRC_PREFIX[];
extern int SERVER_ID;

extern const char status[][30];

struct lang_t
{
	char *language;
	char *compiler;
	char *ext;
	char *ccmd;
};

extern const struct lang_t lang[];

void parse_config (int argc, char **argv);
