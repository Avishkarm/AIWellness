
var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB || window.shimIndexedDB;

	var open = indexedDB.open("doctorUserCase", 1);

function systemdetailDB(systemdetail) {



	// Create the schema
	open.onupgradeneeded = function() {
	    var db = open.result;
	    var store = db.createObjectStore("systemdetails", {keyPath: "priority_id"});
	};

	open.onsuccess = function() {
		console.log("Transcation Started");
	    // Start a new transaction
	    var db = open.result;
	    var tx = db.transaction("systemdetails", "readwrite");
	    var store = tx.objectStore("systemdetails");
	    //var index = store.index("NameIndex");

	    // Add some data
	    for (var i in systemdetail) {
				if(systemdetail.hasOwnProperty(i)){
					var dt ={
							'priority_id':systemdetail[i]['priority_id'],
							'sr_no':systemdetail[i]['sr_no'],
							'system':systemdetail[i]['system'],
							'organ':systemdetail[i]['organ'],
							'subOrgan':systemdetail[i]['subOrgan'],
							'diagnosis':systemdetail[i]['diagnosis'],
							'embryologcial':systemdetail[i]['embryologcial'],
							'miasm':systemdetail[i]['miasm'],
							 };
		        	store.put(dt);      
				}
		}
    
	    // Close the db when the transaction is done
	    tx.oncomplete = function() {
	    	console.log("Transcation Complete");
	        db.close();
	    };
	}
}


function InvestdetailDB(systemdetail) {

	// Create the schema
	open.onupgradeneeded = function() {
	    var db = open.result;
	    var store = db.createObjectStore("Investdetail", {keyPath: "priority_id"});
	};

	open.onsuccess = function() {
	    // Start a new transaction
	    var db = open.result;
	    var tx = db.transaction("systemdetails", "readwrite");
	    var store = tx.objectStore("systemdetails");
	    //var index = store.index("NameIndex");

	    // Add some data
	    for (var i in systemdetail) {
				if(systemdetail.hasOwnProperty(i)){
					var dt ={
							'priority_id':systemdetail[i]['priority_id'],
							'sr_no':systemdetail[i]['sr_no'],
							'system':systemdetail[i]['system'],
							'organ':systemdetail[i]['organ'],
							'subOrgan':systemdetail[i]['subOrgan'],
							'diagnosis':systemdetail[i]['diagnosis'],
							'embryologcial':systemdetail[i]['embryologcial'],
							'miasm':systemdetail[i]['miasm'],
							 };
		        	store.put(dt);      
				}
		}
    
	    // Close the db when the transaction is done
	    tx.oncomplete = function() {
	        db.close();
	    };
	}
}