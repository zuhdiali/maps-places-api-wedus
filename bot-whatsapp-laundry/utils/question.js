const rl = require("readline");

function question(text = "question") {
  return new Promise((resolve) => {
    const rl = require("readline").createInterface({
      input: process.stdin,
      output: process.stdout,
    });
    rl.question(`\x1b[32;1m?\x1b[0m\x20\x1b[1m${text}\x1b[0m`, (answer) => {
      rl.close();
      resolve(answer);
    });
  });
}

module.exports = question;
