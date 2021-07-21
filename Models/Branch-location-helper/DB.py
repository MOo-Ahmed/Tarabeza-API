import mysql.connector

class DB:
  def __init__(self):
    self.mydb = mysql.connector.connect(
      host="localhost",
      user="root",
      password="",
      database="nofipayn_restaurant_reservations_system"
    )
  
  def fetchLocations(self, rid, threshold):
    mycursor = self.mydb.cursor()
    res = []
    dict = []
    data = [rid, threshold]
    query = '''SELECT customers.longitude AS lng, customers.latitude AS ltd, 
        111.111 * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(customers.Latitude))
              * COS(RADIANS(restaurants.Latitude))
              * COS(RADIANS(customers.Longitude - restaurants.Longitude))
              + SIN(RADIANS(customers.Latitude))
              * SIN(RADIANS(restaurants.Latitude))))) AS distance_in_km 
              
      FROM customers INNER JOIN (select DISTINCT customer_id AS cid, restaurant_id AS rid FROM reservations WHERE restaurant_id = %s) AS r 
      ON customers.id = r.cid 
      INNER JOIN restaurants
      ON restaurants.id = r.rid
      HAVING distance_in_km >= %s''' %tuple(data)

    mycursor.execute(query)
    myresult = mycursor.fetchall()
    return myresult