// specail judge sample files.
// for problem 1150
#include <stdio.h>
#include <string.h>

int w[3][8]={{7,6,5,4,3,2,1,0},{3,0,1,2,5,6,7,4},{0,6,1,3,4,2,5,7}};
int s[8],t[8],s1[8],i,j,k,n,m,e;
char c[1024*100];
int ret;

int main(int sn,char *ss[])
{
	if (sn!=4) return 0;

	FILE *f1 = fopen(ss[1],"r");
	FILE *f2 = fopen(ss[2],"r");
	FILE *f3 = fopen(ss[3],"r");

    while (1) {
	    fscanf(f1,"%d",&n);
	    if (n==-1) break;
	    for (i=0;i<=3;i++) fscanf(f1,"%d",&t[i]);
	    for (i=7;i>=4;i--) fscanf(f1,"%d",&t[i]);

	    fscanf(f2,"%d",&m);
	    fgets(c,1024,f2);

//        printf("%d %d\n",n,m);
        
    	if (fscanf(f3,"%d",&e)==1) {
	    	if (m==-1) {
		    	if (e==-1) ret=1;
			    else ret=0;
		    } else {
			    if (e<=n) {
				    if (m>0) fscanf(f3,"%s",c); else c[0]=0;
				    if (strlen(c)==e) {
				        for (i=0;i<8;i++) s[i]=i+1;
					    for (k=0;k<e;k++) if ('A'<=c[k] && c[k]<='C') {
						    for (i=0;i<8;i++) s1[i]=s[i];
						    for (i=0;i<8;i++) s[i]=s1[w[c[k]-'A'][i]];
					    } else { ret=0; break; }
					    if (k>=e) {
						    for (i=0;i<8 && s[i]==t[i];i++);
						    if (i<8) ret=0; else ret=1;
					    }
				    } else ret=0;
			    } else ret=0;
		    }
	    } else ret=0;
	    if (ret==0) { printf("no"); return 0; }
	}
	printf("yes");
	return 0;
}

