#CPSC466Webchat

#Login Unit Test via Selenium
import time
from selenium import webdriver


def testHappyPath(driver):
    driver.get('http://origincommodity.com/login')
    time.sleep(5)
    login = driver.find_element_by_id("inputEmail")
    password = driver.find_element_by_id("inputPassword")

    login.send_keys('roger.hoang@csu.fullerton.edu')
    password.send_keys('password')
    signinBtn = driver.find_element_by_class_name("btn-signin")

    signinBtn.click()


def testBadPath(driver):
    driver = webdriver.Chrome("C:/Users/rhoang/Desktop/selenium/chromedriver.exe")
    driver.get('http://origincommodity.com/login')
    time.sleep(5)
    login = driver.find_element_by_id("inputEmail")
    password = driver.find_element_by_id("inputPassword")

    login.send_keys('roger.hoang@yahoo.com')
    password.send_keys('wrongpassword')
    signinBtn = driver.find_element_by_class_name("btn-signin")

    signinBtn.click()

def switch(case,driver):
    return{
        1: testHappyPath(driver),
        2: testBadPath(driver,)
    }[case]

def main():

    #********************************************************************#
    # download Chrome web driver here:
    # https://chromedriver.storage.googleapis.com/index.html?path=2.29/

    #********************************************************************#
    # change driver path before running
    driver = webdriver.Chrome("C:/Users/rhoang/Desktop/selenium/chromedriver.exe")

    print("MENU")
    print("1. Happy Path Test Case")
    print("2. Bad Path Test Case")

    case = input("Enter an option to start the test: ")

    switch(case,driver)

    print("Done!")
    time.sleep(10)



if __name__ == '__main__':
    main()
