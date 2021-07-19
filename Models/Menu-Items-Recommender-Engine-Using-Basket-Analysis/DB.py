import mysql.connector

class DB:
  def __init__(self):
    self.mydb = mysql.connector.connect(
      host="localhost",
      user="root",
      password="",
      database="nofipayn_restaurant_reservations_system"
    )
  def Convert(self, lst):
    res_dct = {"id" : int(lst[0]), "name" : str(lst[1]) , "price" : int(lst[2]), "category_id" : int(lst[3]) }
    return res_dct

  def getRecommendations(self, suffix, n, restID):
    mycursor = self.mydb.cursor()
    Items = []
    for i in range (0, len(suffix)) :
      query = '''SELECT items.id AS ItemID, items.name AS ItemName, items.price AS price, items.category_id AS CategoryID, COUNT(*) AS ItemCount from 
      (SELECT order_details.meal_id, order_details.order_id, orders.id, orders.restaurant_id 
      FROM orders INNER JOIN order_details 
      ON order_details.order_id = orders.id AND orders.restaurant_id = %s ) AS Q1 
      INNER JOIN items ON Q1.meal_id = items.id 
      AND items.id IN (SELECT items.id from items WHERE items.restaurant_id = %s AND items.category_id = %s) 
      GROUP BY ItemName ORDER BY `ItemCount` DESC  LIMIT %s '''
      #Query = "SELECT id, name, price FROM items where restaurant_id = %s" %ID
      val = (restID,restID, int(suffix[i]), n)
      mycursor.execute(query, val)
      myresult = mycursor.fetchall()
      
      for x in myresult:
        y = self.Convert(x)
        #print(y)
        Items.append(y)
    return Items
