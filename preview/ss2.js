const puppeteer = require("puppeteer");

(async () => {
  const browser = await puppeteer.launch({ headless: "new", args: ["--no-sandbox"] });
  const page = await browser.newPage();
  await page.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1.5 });
  
  const filePath = "file:///D:/Peace%20Institute/Website/preview/courses-preview-v2.html";
  await page.goto(filePath, { waitUntil: "networkidle0", timeout: 30000 });
  await new Promise(r => setTimeout(r, 3500));
  
  await page.evaluate(() => document.querySelectorAll(".reveal").forEach(el => el.classList.add("in")));
  await new Promise(r => setTimeout(r, 600));

  await page.screenshot({ path: "D:/Peace Institute/Website/preview/v2-full.png", fullPage: true });

  for (const id of ["courses", "teachers", "cta"]) {
    const el = await page.$("#" + id);
    if (el) await el.screenshot({ path: `D:/Peace Institute/Website/preview/v2-${id}.png` });
  }

  await browser.close();
  console.log("Done!");
})();
