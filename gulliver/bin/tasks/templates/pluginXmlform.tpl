<?xml version="1.0" encoding="UTF-8"?>
<dynaForm name="{className}" width="600" mode="edit" enableTemplate="0" border="0">
<title1 type="title" >
  <en>{className} form</en>
</title1>

<!-- START BLOCK : fields -->
<{name} type="{type}" size='{size}' maxlength='{maxlength}' >
  <en>{label}
<!-- START BLOCK : values -->
    <option name="{value}">{label}</option>
<!-- END BLOCK : values --> 
  </en>
</{name}>

<!-- END BLOCK : fields --> 

<BTN_SUBMIT type="submit" >
  <en>save</en>
</BTN_SUBMIT>
</dynaForm>