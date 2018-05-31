#!/usr/bin/env python
# -*- coding:utf-8 -*-

import pymysql
import time,datetime
import configparser

def get_cur_time():
    return time.strftime('%Y-%m-%d %H:%M:%S',time.localtime(time.time()))

class outLog:
    def printbegin(self):
        print("Begin Time :"+get_cur_time())
    def printend(self):
        print("End Time :"+get_cur_time());
        
class StartManager:
    def __init__(self):
        self._log = outLog();
        cf = configparser.ConfigParser()
        cf.read("C:/wamp64/www/ttxz/tools/python/mysql.conf")
        self._conn = pymysql.connect(host=cf.get("db","db_host"),
                                     port=int(cf.get("db","db_port")),
                                     user=cf.get("db","db_user"),
                                     passwd=cf.get("db","db_pass"),
                                     db=cf.get("db","db_database"))
        if(self._conn != None):
            print("Connect to log DB is ok!\n\r")
        else:
            print("Connect to log DB failed!\n\r")

    def close_conn(self):
        self._conn.close();
        
    def do_start(self):
        print("-------do_start--------")
        sql = "update {} set is_share=0,left_per_use=1,left_share_use=0".format("tp_wx_user");
        cursor = self._conn.cursor()
        cursor.execute(sql)
        self._conn.commit()
        self.close_conn()

if __name__ == "__main__":
    start = StartManager()
    start.do_start()