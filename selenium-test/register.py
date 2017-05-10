#CPSC466Webchat

#Login Unit Test via Selenium
import time
from selenium import webdriver


def testHappyPath(driver):
    driver.get('http://origincommodity.com/register')
    time.sleep(5)
    firstname = driver.find_element_by_id("first-name-input")
    lastname = driver.find_element_by_id("last-name-input")
    email = driver.find_element_by_id("email-input")
    password = driver.find_element_by_id("password-input")
    password_confirm = driver.find_element_by_id("password-match-input")

    firstname.send_keys("test")
    lastname.send_keys("guy")
    email.send_keys("testguy@csu.fullerton.edu")
    password.send_keys("password")
    password_confirm.send_keys("password")

    driver.find_element_by_xpath("//select[@name='Major']/option[text()='Computer Science']").click()


    registerBtn = driver.find_element_by_class_name("btn-success")

    registerBtn.click()


def testBadPath(driver):
    driver.get('http://origincommodity.com/register')
    time.sleep(5)
    firstname = driver.find_element_by_id("first-name-input")
    lastname = driver.find_element_by_id("last-name-input")
    email = driver.find_element_by_id("email-input")
    password = driver.find_element_by_id("password-input")
    password_confirm = driver.find_element_by_id("password-match-input")

    firstname.send_keys("other")
    lastname.send_keys("testguy")
    email.send_keys("testguy@some.other.school.edu")
    password.send_keys("Badpassword")
    password_confirm.send_keys("badPassword")

    driver.find_element_by_xpath("//select[@name='Major']/option[text()='Art']").click()


    registerBtn = driver.find_element_by_class_name("btn-success")

    registerBtn.click()

def main():

    #********************************************************************#
    # download Chrome web driver here:
    # https://chromedriver.storage.googleapis.com/index.html?path=2.29/

    #********************************************************************#
    # change driver path before running
    driver = webdriver.Chrome("C:/Users/Roger/Desktop/CPSC466-WebChat/selenium-test/chromedriver.exe")

    print("MENU")
    print("1. Happy Path Test Case")
    print("2. Bad Path Test Case")


    testHappyPath(driver)
    time.sleep(5)
    testBadPath(driver)
    time.sleep(5)

if __name__ == '__main__':
    main()
