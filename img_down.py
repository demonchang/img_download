# -*- coding: utf-8 -*-
import urllib2
import re
import time
import os

#显示下载进度
def schedule(a,b,c):
  '''''
  a:已经下载的数据块
  b:数据块的大小
  c:远程文件的大小
   '''
  per = 100.0 * a * b / c
  if per > 100 :
    per = 100
  print '%.2f%%' % per

def getHtml(url):
  try:
    request = urllib2.Request(url)
    response = urllib2.urlopen(request)
    return response.read()
  except urllib2.URLError, e:
    if hasattr(e,"code"):
        print e.code
    if hasattr(e,"reason"):
        print e.reason
  

def downloadImg(html):
  reg = r'src="(.+?\.jpg)" pic_ext'
  imgre = re.compile(reg)
  imglist = re.findall(imgre, html)  
  x = 0
  for imgurl in imglist:
    target = './%s.jpg' % x
    print 'Downloading image to location: ' + target + '\nurl=' + imgurl
    image = urllib.urlretrieve(imgurl, target)
    x += 1
  return image;

  
  
if __name__ == '__main__':
  
  html = getHtml("http://www.22mm.cc/")

  downloadImg(html)
  print "Download has finished."