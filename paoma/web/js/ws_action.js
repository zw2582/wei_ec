/**
 * http://usejsdoc.org/
 */
export default (function(){
	var ws;
	var messagefn;
	var openfn;
	var closefn;
	var vue;
	
	function conn(vueobj, onopen, onmessage, onclose) {
		openfn = onopen;
		messagefn = onmessage;
		closefn = onclose;
		vue = vueobj;
		
		window.addEventListener("online", connect, false)
		
		connect()
	}
	
	function connect(){
		if (!ws ||ws.readyState == 3) {
			ws = new WebSocket(process.env.WS_ADDR+'?source=phone&uid='+vue.user.uid)
		}
		ws.onopen = openfn;
		ws.onmessage = messagefn;
		ws.onclose = closefn;
	}
	
	//摇动
	function play(uid) {
		ws.send(JSON.stringify({action:'play','uid':uid,'count':1}));
	}
	
	return {conn, play}
})();