function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

      var recognition = new webkitSpeechRecognition();

      recognition.continuous = false;
      recognition.interimResults = false;

      recognition.lang = "en-US";
      recognition.start();

      recognition.onresult = function(e) {
        document.getElementById('search_input').value
                                 = e.results[0][0].transcript;
       $('.mic-wrap').removeClass('active');
        recognition.stop();

        document.getElementById('search-form').submit();

      };

      recognition.onerror = function(e) {
        recognition.stop();
        $('.mic-wrap').removeClass('active');
      }

    }
  }


$('#microphone').on('click', function(e){
  startDictation();
  $('.mic-wrap').addClass('active');
  setTimeout(function(){ $('.mic-wrap').removeClass('active'); }, 6000);
});
