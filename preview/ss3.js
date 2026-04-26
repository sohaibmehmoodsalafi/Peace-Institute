const puppeteer = require("puppeteer");

(async () => {
  const browser = await puppeteer.launch({ headless: "new", args: ["--no-sandbox"] });
  const page = await browser.newPage();
  await page.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1.5 });
  
  await page.goto("file:///D:/Peace%20Institute/Website/preview/courses-preview-v3.html", { waitUntil: "networkidle0", timeout: 30000 });
  await new Promise(r => setTimeout(r, 3500));
  await page.evaluate(() => document.querySelectorAll(".reveal").forEach(el => el.classList.add("in")));
  await new Promise(r => setTimeout(r, 500));

  // Hero section
  await page.screenshot({ path: "D:/Peace Institute/Website/preview/v3-hero.png", clip: { x:0, y:0, width:1440, height:700 } });
  
  // Features bar
  await page.screenshot({ path: "D:/Peace Institute/Website/preview/v3-features.png", clip: { x:0, y:700, width:1440, height:280 } });

  // Courses section
  const courses = await page.$("#courses");
  if (courses) await courses.screenshot({ path: "D:/Peace Institute/Website/preview/v3-courses.png" });

  // Teachers section
  const teachers = await page.$("#teachers");
  if (teachers) await teachers.screenshot({ path: "D:/Peace Institute/Website/preview/v3-teachers.png" });

  // CTA section
  const cta = await page.$("#cta");
  if (cta) await cta.screenshot({ path: "D:/Peace Institute/Website/preview/v3-cta.png" });

  // Full page
  await page.screenshot({ path: "D:/Peace Institute/Website/preview/v3-full.png", fullPage: true });

  await browser.close();
  console.log("Done!");
})();
