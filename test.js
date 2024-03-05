import puppeteer from 'puppeteer';
import axiosClient from './resources/js/axiosClient.js';

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto('https://www.facebook.com/groups/543629622444787/');

    // Chờ trang load hoàn chỉnh
    await page.waitForSelector('h1');

    // Lấy nội dung của thẻ h1
    const heading = await page.$eval('h1', element => element.textContent);
    // const href = await page.evaluate(() => {
    //     const anchor = document.querySelector('a[href="/groups/543629622444787/members/"]');
    //     return anchor ? anchor : null;
    // });
    const href = await page.$eval('a[href="/groups/543629622444787/members/"]', element => element.textContent);
    axiosClient.get('https://testlrv.praz.vn/api/country/state?id=15').then(({ data }) => {
        console.log(data);
    })
    console.log('TÊN NHÓM: ', heading);
    console.log('THÀNH VIÊN:', href);

  

    await browser.close();
})();
