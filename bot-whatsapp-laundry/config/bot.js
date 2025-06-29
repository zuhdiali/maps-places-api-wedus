const { time } = require("console");
const fs = require("fs");
const path = require("path");

const pkg = JSON.parse(fs.readFileSync(path.join("package.json")));

global.bot = {
  name: "Zuhdi Ali Hisyam",
  number: "6282328839199",
  version: pkg.version,
  prefix: ["."],
  splitArgs: "|",
  locale: "id",
  timezone: "Asia/Jakarta",
  adsUrl: "https://www.youtube.com/@zuhdi_ali_hisyam",
  newsletterJid: "",
  commands: (() => {
    return [];
  })(),
  setting: JSON.parse(fs.readFileSync("./config/setting.json")),
  saveSetting: function () {
    fs.writeFileSync(
      "./config/setting.json",
      JSON.stringify(global.bot.setting)
    );
    return global.bot.setting;
  },
};

global.owner = {
  name: "Zuhdi Ali Hisyam",
  number: "6282328839199",
};

global.db = {
  user: [],
  group: [],
  premium: [],
  save: async function (dbName) {
    fs.writeFileSync(name, JSON.stringify(data));
  },
};
