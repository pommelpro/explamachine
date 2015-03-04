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
		return None
	else:
		redirectNumber= redirectNumber[0][0]
		redirectNumber= findId(redirectNumber)
		return str(redirectNumber)

	
if __name__ =="__main__":

	db = MySQLdb.connect(host="downey-n2.cs.northwestern.edu", user="wikification", passwd="Wikific@tion", db="wikapidia0p3")
	cur = db.cursor()

	commonTopics = [16642, 5976324, 21255, 4536940, 1155086, 36277904, 32853799, 17296654, 68756, 6114325, 5910, 22732312, 9897626, 19085596, 435781, 4651248, 674339, 29529511, 4775080, 3895836, 31525546, 8465707, 27987245, 7191090, 66742, 160108, 8465722, 7035579, 17311933, 38152017, 26051138, 37397829, 30052934, 9426378, 12989388, 16334929, 16822866, 14316243, 25549966, 3671, 14427608, 6194265, 15578, 10755423, 25495776, 404065, 5135075, 5891556, 32368997, 24323814, 38101841, 37999465, 39055504, 40667883, 3607276, 29436605, 1058416, 584562, 19220341, 38101111, 25533945, 30202, 620518]


	redirect= findRedirects(3999390)

	#commonTopics1= [];
	#for index in range(0, len(commonTopics)):
	#	commonTopics1= findRedirects(commonTopics[index])
	print redirect	
	


	

