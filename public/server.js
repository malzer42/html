const express = require('express');
const bodyParser = require('body-parser');
const axios = require('axios');

const app = express();
app.use(bodyParser.urlencoded({ extended: true }));

const TURNSTILE_SECRET_KEY = '0x4AAAAAABT_sTDDTZJFSmkapTBR73o_47U';

app.post('/submit-form', async (req, res) => {
  const token = req.body['cf-turnstile-response'];
  if (!token) {
    return res.status(400).send('Missing CAPTCHA token');
  }

  try {
    const verifyURL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    const result = await axios.post(verifyURL, null, {
      params: {
        secret: TURNSTILE_SECRET_KEY,
        response: token,
        remoteip: req.ip
      }
    });

    if (result.data.success) {
      // CAPTCHA verified – process form
      res.send('✅ CAPTCHA passed. Form submitted!');
    } else {
      res.status(403).send('❌ CAPTCHA failed');
    }
  } catch (err) {
    console.error(err);
    res.status(500).send('Server error');
  }
});

app.listen(3000, () => {
  console.log('Server running on http://localhost:3000');
});

