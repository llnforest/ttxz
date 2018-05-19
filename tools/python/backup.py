# !/usr/bin/env python
# -*- coding:utf-8 -*-

import configparser
import time
import os
import datetime

class DbBackup:

    def __init__(self):
        self._cf = configparser.ConfigParser()
        self._cf.read("D:/wamp64/www/ttxz/tools/python/mysql.conf")
        self._dump_file = r"{}{}_backup_{}.sql".format(self._cf.get("backup","backup_dir"),self._cf.get("db","db_database"),time.strftime("%Y%m%d%H"))
        self._old_file = r"{}{}_backup_{}.sql".format(self._cf.get("backup","backup_dir"),self._cf.get("db","db_database"),(datetime.datetime.now()-datetime.timedelta(days=30)).strftime("%Y%m%d%H"))

    def do_backup(self):
        self.mysql_dump()
        self.db_clean()

    def mysql_dump(self):
        print('---------mysql_dump------------')
        os.system("mysqldump -h{} -u{} -p{} {} --default-character-set={} > {}".format(self._cf.get("db","db_host"),self._cf.get("db","db_user"),self._cf.get("db","db_pass"),self._cf.get("db","db_database"),self._cf.get("db","db_charset"),self._dump_file))

    def db_clean(self):
        print('---------db_clean------------')
        os.system("del {}".format(self._old_file))

if __name__ == "__main__":
    db_backup = DbBackup()
    db_backup.do_backup()
        