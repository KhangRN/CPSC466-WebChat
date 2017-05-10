#CPSC466Webchat

#Login Unit Test via Selenium
import time
from selenium import webdriver


def testHappyPath(driver):

    # goto /register
    # driver.get('http://origincommodity.com/register')
    # time.sleep(5)
    # firstname = driver.find_element_by_id("first-name-input")
    # lastname = driver.find_element_by_id("last-name-input")
    # email = driver.find_element_by_id("email-input")
    # password = driver.find_element_by_id("password-input")
    # password_confirm = driver.find_element_by_id("password-match-input")
    #
    # firstname.send_keys("test2")
    # lastname.send_keys("guy2")
    # email.send_keys("testguy2@csu.fullerton.edu")
    # password.send_keys("password")
    # password_confirm.send_keys("password")
    #
    # driver.find_element_by_xpath("//select[@name='Major']/option[text()='Computer Science']").click()
    #
    #
    # registerBtn = driver.find_element_by_class_name("btn-success")
    #
    # registerBtn.click()

    # sleep for 2 seconds to wait for form-data to post
    time.sleep(2)


    # goto /login
    driver.get('http://origincommodity.com/login')
    time.sleep(5)
    loginLogin = driver.find_element_by_id("inputEmail")
    passwordLogin = driver.find_element_by_id("inputPassword")

    # loginLogin.send_keys('testguy2@csu.fullerton.edu')
    # passwordLogin.send_keys('password')
    loginLogin.send_keys('roger.hoang@csu.fullerton.edu')
    passwordLogin.send_keys('password')
    signinBtn = driver.find_element_by_class_name("btn-signin")

    signinBtn.click()


    # sleep for 5 seconds to wait for chat ui to load
    time.sleep(5)

    msgarea = driver.find_element_by_id("msg-content")
    sendmessageBtn = driver.find_element_by_id("send-message-button")
    msgarea.send_keys("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.")

    sendmessageBtn.click()


    time.sleep(5)




def main():

    #********************************************************************#
    # download Chrome web driver here:
    # https://chromedriver.storage.googleapis.com/index.html?path=2.29/

    #********************************************************************#
    # change driver path before running
    driver = webdriver.Chrome("C:/Users/Roger/Desktop/CPSC466-WebChat/selenium-test/chromedriver.exe")

    print("MENU")
    print("1. Happy Path Test Case")


    testHappyPath(driver)

if __name__ == '__main__':
    main()
