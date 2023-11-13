import re
from unittest import result
from bs4 import BeautifulSoup
import requests

webSite = 'https://www.marketwatch.com/investing/stock/bhi/charts?countrycode=co'
result = requests.get(webSite)
content = result.text

soup = BeautifulSoup(content, 'lxml')

box = soup.find('bg-quote', class_='value').get_text()

print(box)