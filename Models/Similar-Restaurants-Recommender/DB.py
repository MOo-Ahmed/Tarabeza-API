import mysql.connector

class DB:
  def __init__(self):
    self.mydb = mysql.connector.connect(
      host="localhost",
      user="root",
      password="",
      database="final"
    )
  def Convert(self, lst):
    res_dct = {"restaurant_id" : int(lst[0]), "restaurant_name" : str(lst[1]) , "category_name" : str(lst[2]), "longitude": float(lst[3]), "latitude": float(lst[4])}
    return res_dct

  def getCustomerLocation(self, cid):
    mycursor = self.mydb.cursor()
    query = "SELECT longitude, latitude FROM customers WHERE id = %s" %cid
    #val = (cid)
    loc = []
    mycursor.execute(query)
    myresult = mycursor.fetchall()
    for x in myresult:
      loc.append(x[0])
      loc.append(x[1])
    return loc
  
  def getCustomerPreferences(self, cid):
    mycursor = self.mydb.cursor()
    prefs = []
    query = "SELECT category_id FROM customers_preferences WHERE customer_id = %s" %cid
    mycursor.execute(query)
    myresult = mycursor.fetchall()
      
    for x in myresult:
      prefs.append(x[0])
    return prefs

  def getMatchingRestaurants(self, cid):
    rests = []
    mycursor = self.mydb.cursor()
    prefs = self.getCustomerPreferences(cid)
    for i in range (0, len(prefs)) :
      query = '''SELECT restaurants.id AS id, restaurants.name AS name, Q1.category_name AS category_name,
      restaurants.longitude AS longitude, restaurants.latitude AS latitude
      FROM (SELECT restaurant_has_categories.restaurant_id AS restaurant_id, categories.name AS category_name
      FROM restaurant_has_categories INNER JOIN categories
      ON restaurant_has_categories.category_id = categories.id AND restaurant_has_categories.restaurant_id IN
      (SELECT restaurant_has_categories.restaurant_id FROM restaurant_has_categories WHERE restaurant_has_categories.category_id = %s)) AS Q1
      INNER JOIN restaurants ON restaurants.id = Q1.restaurant_id;'''  %prefs[i]
      #val = (prefs[i])
      mycursor.execute(query)
      myresult = mycursor.fetchall()
      
      for x in myresult:
        y = self.Convert(x)
        #print(y)
        rests.append(y)
    return rests
