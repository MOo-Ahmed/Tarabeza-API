import pandas as pd
from sklearn.tree import DecisionTreeClassifier 
from sklearn.model_selection import train_test_split 
from sklearn import metrics
'''
from sklearn.tree import export_graphviz
from IPython.display import Image  
import pydotplus
'''
import io
import DB
import sys

class Classifier :
    
    def __init__(self):
        self.filename = "kb.csv"

    def run(self, user_id, rest_id) :
        db = DB.DB()
        cust = db.getCustomer(user_id, rest_id)
        
        if not cust :
            return 'miss'
            
        
        #print(cust[0][0])
        gender = 1
        if cust[0][1] == "female": gender = 0

        test = [[cust[0][0], 1, gender, cust[0][2]]]

        if not cust[0][1] or not cust[0][2] :
            return 'miss'
            

        col_names = ['status', 'distance','name','gender','age']
        # load dataset
        df = pd.read_csv(self.filename)
        df = df.iloc[1:]
        feature_cols = ['distance','name', 'gender', 'age']
        X = df[feature_cols] # Features
        y = df['status'] # Target variable

        #X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=1) # 70% training and 30% test
        # Create Decision Tree classifer object
        clf = DecisionTreeClassifier()

        # Train Decision Tree Classifer
        clf = clf.fit(X,y)

        #Predict the response for test dataset
        y_pred = clf.predict(test)
        if y_pred[0] == 0: return "miss"
        else: return "activate"

        '''
        # Model Accuracy
        print("Accuracy:",metrics.accuracy_score(y_test, y_pred))

        #Create a decision tree photo to view the rules
        
        dot_data = io.StringIO()
        export_graphviz(clf, out_file=dot_data,  
                filled=True, rounded=True,
                special_characters=True,feature_names = feature_cols,class_names=['0','1'])
        graph = pydotplus.graph_from_dot_data(dot_data.getvalue())  
        graph.write_png('tree.png')
        Image(graph.create_png())
        print('finished')
        '''