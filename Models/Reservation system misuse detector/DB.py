import mysql.connector
import pandas as pd



class DB:
  def __init__(self):
    self.mydb = mysql.connector.connect(
      host="localhost",
      user="root",
      password="",
      database="nofipayn_restaurant_reservations_system"
    )
  
  def getCustomer(self, user_id, rest_id):
    mycursor = self.mydb.cursor()
    res = []
    dict = []
    data = [user_id, user_id, rest_id]
    query = '''SELECT 111.111 * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(C.Latitude))
              * COS(RADIANS(restaurants.Latitude))
              * COS(RADIANS(C.Longitude - restaurants.Longitude))
              + SIN(RADIANS(C.Latitude))
              * SIN(RADIANS(restaurants.Latitude))))) AS distance, 
        U.gender AS gender, TIMESTAMPDIFF(YEAR, U.date_of_birth, CURDATE()) AS age

      FROM (SELECT * FROM customers WHERE user_id = %s) AS C INNER JOIN (SELECT * FROM users WHERE id = %s) AS U 
      ON C.user_id = U.id 
      INNER JOIN restaurants
      ON restaurants.id = %s''' %tuple(data)

    mycursor.execute(query)
    myresult = mycursor.fetchall()
    return myresult