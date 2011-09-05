<?xml version="1.0" encoding="UTF-8" ?>
<dynaForm name="login" version="1.0" basedir="" xmlform_type="NORMAL" width="400px">
<TITLE type="title">
  <en>Login</en>
</TITLE>
<USR_USERNAME type="text" size="30" maxlength="32" validate="Login">
  <en>User</en>
</USR_USERNAME>
<USR_PASSWORD type="password" size="30" maxlength="32">
  <en>Password</en>
</USR_PASSWORD>
<USER_LANG type="dropdown">
SELECT LAN_ID, LAN_NAME FROM LANGUAGE WHERE LAN_ENABLED = "1" ORDER BY LAN_WEIGHT DESC
  <en>Language</en>
</USER_LANG>
<BSUBMIT type="submit">
  <en>Login</en>
</BSUBMIT>
</dynaForm>

