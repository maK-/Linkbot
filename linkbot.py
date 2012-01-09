#!/usr/local/bin/env python3.1
# -*- coding: iso-8859-15 -*-

""" An IRC bot that does multiple things with urls"""

import socket, string, _mysql, sys
from mechanize import Browser
from sgmllib import SGMLParser
from threading import Timer
import MySQLdb as mdb

#Join Irc
net = '' #Insert Irc network
port = 6667
nick = 'linkbot'
chan = '#lobby,#bots' #channels to join
owner = 'mak'
ident = 'mak'
readbuffer = ''

#Connect the bot
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((net,port))
s.send('USER '+ident+' '+net+' bla :'+owner+'\r\n')
s.send('NICK '+nick+'\r\n')
s.send('JOIN '+chan+'\r\n')
print(s.recv(2048))

#Display text properly
keysss = {'&#039;':'\'','&#39;':'\'','&#8217;':'\'','&#x20AC;':'€','(':'',')':'','@':'','&amp;':'&','&quot;':'"','&Eacute;':'E','\n':''}

serverval = '' #Server name
t = ''#Array to hold link values

#make changes and replace words in text
def replace(text, wordDict):
    for key in wordDict:
        text = text.replace(key, wordDict[key])
    return text

#Get the title of the linked page
def getTitle(url):
    b = Browser()        
    try:
        b.addheaders = [('User-agent', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008071615 Fedora/3.0.1-1.fc9 Firefox/3.0.1')]
        b.set_handle_robots(False)
        b.open(url)
        page = b.response().read()
        try:
            title = string.split(page, '<title>')
            pageTitle = string.split(title[1],'</title>')
            result = pageTitle[0]            
        except IndexError, e:
            return ''
        return result
        
    except IOError, e:
        return ''

#what kind of link is it
def verifyLink(url):
    v = Browser()
    try:
        v.addheaders = [('User-agent', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008071615 Fedora/3.0.1-1.fc9 Firefox/3.0.1')]
        v.set_handle_robots(False)
        v.open(url)
        page = v.response().read()
        if '<html>' in page or '<title>' in page:
            return 'Page'
        else:
            return 'Image'
    except IOError, e:
        return '404'
                 
class TextExtracter(SGMLParser):
    def __init__(self):
        self.text = []
        SGMLParser.__init__(self)
    def handle_data(self, data):
        self.text.append(data)
    def getvalue(self):
        return ''.join(ex.text)
        
def pong():
    try:
        x = Timer(175.0, pong)
        x.daemon=True
        x.start()
        s.send('PONG '+serverval+'\r\n')
    except (KeyboardInterrupt, SystemExit):
        x.cancel()

#strip starting : and following ![server details]
def getName(name):
	nix = string.split(name,"!")
	name = nix[0]
	y = name[1:]
	return y

#Add a link to database.        
def addLink(url,title,user,count):
    if count == '':
        count = '1'
    con = None
    try:    
        con = _mysql.connect('mysql-location','mak','password','mak')
        title = replace(title,keysss)
        con.query("INSERT INTO links (link, title, user, count) VALUES ('"+url+"', '"+title+"', '"+user+"', '"+count+"')")
        result = con.use_result()
        print('Added.')   
    except _mysql.Error, e:
        print "Error %d: %s" % (e.args[0], e.args[1])

    finally:    
        if con:
            con.close()
            
def randLink():
    key={"(('":"","',),)":""}
    con = _mysql.connect('mysql-location','mak','password','mak')
    con.query("select link from links order by RAND() limit 1")
    res = con.use_result()
    resu = str(res.fetch_row())
    resu = replace(resu, key)
    print(resu)
    return resu
    
pong() 
while True:
    viewcount = ''
    nix = ''
    link = '' #stores link
    head = '12Title: ' #lobbyype of link
    totalLink = '' #For file storage
    tochannel = '#bots'
    readbuffer=readbuffer+s.recv(4096)
    temp=string.split(readbuffer, "\n")
    readbuffer=temp.pop()
    for line in temp:
        line=string.rstrip(line)
        line=string.split(line)
        print(line)

    if(line[0]=='PING'):
        s.send('PONG '+line[1]+'\r\n')
        serverval = line[1]
    try:    
	if(line[3]==':!link')and len(line)==4:
		imp = randLink()
	        s.send('PRIVMSG #bots :'+getName(line[0])+': 16Link7=>'+impr+'\r\n')
    except IndexError, e:
        pass
    if(line[1]=='PRIVMSG') and len(line) > 2:
        nix = getName(line[0])
        if nix != 'linkbot':
            for n in range(3,len(line)):
                if 'http://' in line[n] or 'https://' in line[n]:
                    link = line[n]
                    if n == 3:
                        l = line[n]
                        link = l[1:]
                        
                if 'www.' in line[n] and 'http://' not in line[n]:
                    link = 'http://'+line[n]
                    if n == 3:
                        l = line[n]
                        link = 'http://'+l[1:]
                                
            if link != '':  
                link = replace(link, keysss)
                isLink = verifyLink(link)
                if isLink == 'Page':
                    t = getTitle(link)  #Get the title of the webpage
                    t = string.strip(t)
                    t = replace(t, keysss)
                
                    #Custom coloured headings
                    if '- YouTube' in t:
                        head = '1,16You16,5Tube: '
                        z = string.split(t, '- YouTube')
                        t = z[0]
                        tochannel = '#lobby'
                    if 'Redbrick' in t or 'redbrick' in t :
                        head = '5Redbrick: '
                        z = string.split(t, '|')
                        t = z[0]
                    if 'xkcd' in t:
                        head = '15xkcd: '
                        z = string.split(t, ':')
                        t = z[0]
                        tochannel ='#lobby'
                    if ' - Imgur' in t:
                        head = '16imgur: '
                        z = string.split(t, ' - Imgur')
                        t = z[0]
                    if ' | Techdirt' in t:
                        head = '2Tech12dirt: '
                        z = string.split(t, ' | Techdirt')
                        t = z[0]            
                    if 'www.google.' in link:
                        head = '2,15G5,15o7,15o2,15g3,15l5,15e: '
                    if 'http://www.reddit.com/' in link:
                        head = '15ಠ_ಠ: '
                    if 'http://www.rte.ie/' in link:
                        head = '16,2RTE: '
                        z = string.split(t, ' - RT')
                        t = z[0]
                    if '| guardian.co.uk' in link:
                        head = '4the5guardian: '
                        z = string.split(t,'| guardian.co.uk')
                        t = z[0]
                    if 'http://tinyurl.com' in link and len(link) > 20:
                        tochannel = '#lobby'

                    if t != '' and link != '':                
                        #if link hasn't been before add it to file
                        if len(t) <= 240:
                            ex = TextExtracter()
                            ex.feed(t)
                            totalLin = ex.getvalue()
                            print(t)                        
                                                
                            if tochannel != '#lobby':
                                s.send('PRIVMSG '+tochannel+' :5['+link+'5] '+head+t+'\r\n')
                                addLink(link, t, nix, viewcount)
                                
                            else:
                                s.send('PRIVMSG '+tochannel+' :'+head+t+'\r\n')
                                addLink(link, t, nix, viewcount)
                        
                if isLink == 'Image':
                    if('.jpg' in link)or('.jpeg' in link)or('.png' in link)or('.gif' in link):                        
                        try:
                            linkx = string.split(link,'/')
                            t = linkx[len(linkx)-1]
                            print(t)
                            addLink(link, t, nix, '0')
                        except IndexError, e:
                            pass
                if isLink == '404':
                    pass