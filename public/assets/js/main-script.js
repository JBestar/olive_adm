

/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var mainDropdownBtn = document.getElementsByClassName("main-dropdown-btn");
var i;
var worker; 

for (i = 0; i < mainDropdownBtn.length; i++) {
  mainDropdownBtn[i].addEventListener("click", function() {
    this.classList.toggle("main-dropdown-active-btn");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}

$(document).ready(function() {
	//워커 실행
	startWorker();

	$("textarea").keydown(function(e) {

	    if(e.keyCode === 9) { // tab was pressed

		     // get caret position/selection
		     var start = this.selectionStart;
		     var end = this.selectionEnd;

		     var $this = $(this);
		     var value = $this.val();

		     // set textarea value to: text before caret + tab + text after caret
		     $this.val(value.substring(0, start)
		                    + "\t"
		                    + value.substring(end));

		     // put caret at right position again (add one for the tab)
		     this.selectionStart = this.selectionEnd = start + 1;

		     // prevent the focus lose
		     e.preventDefault();
		}
	});
});




// worker 실행
function startWorker() {

  // Worker 지원 유무 확인
  if ( !!window.Worker ) {

    // 실행하고 있는 워커 있으면 중지시키기
    if ( worker ) {
      stopWorker();
    }

    worker = new Worker( FURL+'/assets/js/worker.js' );
    worker.postMessage( '워커 실행' );    // 워커에 메시지를 보낸다.

    // 메시지는 JSON구조로 직렬화 할 수 있는 값이면 사용할 수 있다. Object등
    // worker.postMessage( { name : '302chanwoo' } );

    // 워커로 부터 메시지를 수신한다.
    worker.onmessage = function( e ) {      
      requestInfo();
      
    };
  }

}


// worker 중지
function stopWorker() {

  if ( worker ) {
    worker.terminate();
    worker = null;
  }

}



function requestInfo() { 

	if(mObjUser != null)
    {
      if(parseInt(mObjUser.mb_level) > LEVEL_COMPANY){
          requestEmployeeInfo();
      }
    
    }
}

