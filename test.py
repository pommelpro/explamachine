import MySQLdb
import sys
import urllib2
import simplejson

def findId(searchName):
	cur.execute("select art_id from en_article where art_title='"+searchName+"'" )
	fetch1 = cur.fetchall()
	try:
		ID= str(fetch1[0][0])
		return ID
	except:
		print searchName+" does not exist"
		sys.exit(0)

def findLinks(searchId):
	cur.execute("select * from en_article_link where to_id= "+searchId)
	fromIdsSearchTopics =  cur.fetchall()
	fromIdsSearchTopics = set([int(i[1]) for i in fromIdsSearchTopics])
	return fromIdsSearchTopics

def findRedirects(searchId):
	cur.execute("select rd_title from en_redirect where rd_from= "+str(searchId))
	redirectNumber = cur.fetchall()
	
	if not redirectNumber:
		return str(searchId)
	else:
		redirectNumber= redirectNumber[0][0]
		redirectNumber= findId(redirectNumber)
		return str(redirectNumber)

	
if __name__ =="__main__":
	searchName1 = sys.argv[1]
	searchName2 = sys.argv[2]

	db = MySQLdb.connect(host="downey-n2.cs.northwestern.edu", user="wikification", passwd="Wikific@tion", db="wikapidia0p3")
	cur = db.cursor()

	ID1= findId(searchName1)
	ID2= findId(searchName2)

	fromIdsSearchTopics1= findLinks(ID1)
	fromIdsSearchTopics2= findLinks(ID2)
	
	commonTopics = fromIdsSearchTopics1.intersection(fromIdsSearchTopics2)
	commonTopics = list(commonTopics)
	commonTopics1= []
	for index in range (0,len(commonTopics)):
		commonTopics1.append(findRedirects(commonTopics[index]))

	dictionary1= {}
	loadJson= simplejson.loads(urllib2.urlopen('http://downey-n2.cs.northwestern.edu:8080/wikisr/sr/sID/'+ID1+'/langID/1').read())
	allTopics= loadJson['result']

	for index in allTopics:
		try:
			dictionary1[index['wikiPageId']]=index['srMeasure']
		except:
			pass
	
	dictionary2={}
	loadJson= simplejson.loads(urllib2.urlopen('http://downey-n2.cs.northwestern.edu:8080/wikisr/sr/sID/'+ID2+'/langID/1').read())
	allTopics1= loadJson['result']

	for index1 in allTopics1:
		try:
			dictionary2[index1['wikiPageId']]=index1['srMeasure']
		except:
			pass

	dictionary3={}
	for item in commonTopics:
		try:
			dictionary3[int(item)]=dictionary1[int(item)]*dictionary2[int(item)]
		except:
			pass

	invertdictionary= sorted(dictionary3.iterkeys(), key=lambda k: dictionary3[k],reverse=True)
	lengthofresult= len(invertdictionary)

	if lengthofresult>5:
		top5= [invertdictionary[0],invertdictionary[1],invertdictionary[2],invertdictionary[3],invertdictionary[4]]
	elif lengthofresult==0 and len(commonTopics)>5:
		top5= [commonTopics[0],commonTopics[1],commonTopics[2],commonTopics[3],commonTopics[4]]
	elif lengthofresult==0:
		top5= commonTopics
	else:
		top5= invertdictionary

	print "There are "+str(len(top5))+" in top5 and they are: "

	completeText = ""
	for topicID in top5:
		answer = simplejson.loads(urllib2.urlopen('http://websail-fe.cs.northwestern.edu:8080/wikifier/resource/article/'+str(topicID)).read())
		try:
			completeText += answer['response']['plainText']
			print topicID
		except:
			print topicID,"nope!"

	completeText = completeText.replace("\n","").replace("\t","")
	completeText = completeText.split(". ")
	
	f = open("completeText.txt","w")
	f.write("\n".join(completeText).encode('ascii', 'ignore'))
	f.close()

