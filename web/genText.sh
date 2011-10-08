#!/bin/bash
find . -name "*.php" > filelist.txt
xgettext --from-code UTF-8 -o locale/zh_CN/LC_MESSAGES/Sicily.po --join-existing -f filelist.txt --copyright-holder="Informatic Lab in Sun Yat-sen University" 
rm filelist.txt