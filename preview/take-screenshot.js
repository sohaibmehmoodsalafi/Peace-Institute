const puppeteer = require("puppeteer");

(async () => {
  const browser = await puppeteer.launch({ headless: "new", args: ["--no-sandbox", "--disable-setuid-sandbox"] });
  const page = await browser.newPage();
  await page.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1.5 });
  
  const filePath = "file:///D:/Peace%20Institute/Website/preview/courses-preview.html";
  await page.goto(filePath, { waitUntil: "networkidle0", timeout: 30000 });
  
  // Wait for fonts & animations
  await new Promise(r => setTimeout(r, 3000));
  
  // Trigger all reveal animations
  await page.evaluate(() => {
    document.querySelectorAll(".reveal").forEach(el => el.classList.add("visible"));
  });
  
  await new Promise(r => setTimeout(r, 500));

  // Full page
  await page.screenshot({
    path: "D:/Peace Institute/Website/preview/puppeteer-full.png",
    fullPage: true
  });

  // Each section
  for (const id of ["courses", "teachers", "cta"]) {
    const el = await page.$("#" + id);
    if (el) await el.screenshot({ path: `D:/Peace Institute/Website/preview/puppeteer-${id}.png` });
  }
  
  await browser.close();
  console.log("All screenshots done!");
})();
