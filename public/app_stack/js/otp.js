function opt(){
  $.ajax({    
    'type' : 'GET',
    'url'   : baseURL+'Cashier/sendOtp',    
    'beforeSend': function() {
     // location.reload();
    },
    'success' : function(data){  
      //data = (data);
      if(data.status == true){
        
      }else{
        
      }      
      console.log('data == ',data);
    },
    'error' :  function(errors){
      console.log(errors);
    },
    'complete': function(response) {           
    }
   });
}

function verifyOpt(otp){
  $.ajax({    
    'type' : 'GET',
    'url'   : baseURL+'Cashier/verifyOTP?otp='+otp,    
    'beforeSend': function() {
     // location.reload();
    },
    'success' : function(data){  
      //data = (data);
      if(data.status == true){
        
      }else{
        
      }      
      console.log('data == ',data);
    },
    'error' :  function(errors){
      console.log(errors);
    },
    'complete': function(response) {           
    }
   });
}