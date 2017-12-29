<?php
?>
<div id="app">{{message}}</div>
<div id="app-2">
  <span v-bind:title="message">
    鼠标悬停几秒钟查看此处动态绑定的提示信息！
  </span>
</div>
<div id="app-3">
	<p v-if="seen">你可以看到我了</p>
</div>

<div id="app-4">
	<ol>
		<li v-for="todo in todos">
		{{todo.text}}
		</li>
	</ol>
</div>

<div id="app-5">
	<p>{{message}}</p>
	<button v-on:click="reverseMessage">逆转消息</button>
</div>

<div id="app-6">
	<p>{{a.name}}</p>
	<p>{{a.message}}</p>
	<p>{{a.sex}}</p>
	<input v-model="a.name">
	<input v-model="a.message">
	<input v-model="a.sex">
</div>

<div id="app-7">
	<span>{{message}}</span>
	<span v-once>{{message}}</span>
	<span v-html="rawhtml"></span>
	<span v-bind:id="message">a</span>
	<button v-bind:disabled="btn_disable">button</button>
	<span>number:{{number+1}}</span><br/>
	<a :href="message">message link</a>
	<button @click="btn_click">click</button><br/>
	<span>模板表达式：{{message.split(' ').reverse().join(' ')}}</span>
	<span>计算属性:{{reverseMessage}}</span>
</div>

<script>
	//app的数据
	var app = new Vue({
		el:"#app",
		data:{
			message:"hello vue"
		}
	})
	var app2 = new Vue({
		  el: '#app-2',
		  data: {
		    message: '页面加载于 ' + new Date().toLocaleString()
		  }
	})
	var app3 = new Vue({
		el:"#app-3",
		data:{
			seen:true
		}
	})
	var app4 = new Vue({
		el:"#app-4",
		data:{
			todos:[
				{text:'好好学些，天天向上'},
				{text:'hello'}
			]
		}
	})
	var app5 = new Vue({
		el:"#app-5",
		data:{
			message:'hello my vue.js'
		},
		methods:{
			reverseMessage:function() {
				this.message = this.message.split(' ').reverse().join(' ')
			}
		}
	})
	var app6 = new Vue({
		el:"#app-6",
		data:<?=$params?>
	})

	var app7 = new Vue({
		el:"#app-7",
		data:{
			message:"132465798",
			rawhtml:'<h1>abcd</h1>',
			btn_disable:true,
			number:12
		},
		methods:{
			btn_click:function(){
				console.log('caca');
			}
		},
		//watch vs computed:watch提供执行异步操作，computed做不到；computed可以缓存依赖
		computed:{
			reverseMessage:{
				get:function(){
					return this.message.split('').reverse().join('')
				},
				set:function(val) {
					console.log('caca'+val)
					this.message=val
				}
			}
		}
	})
</script>

<!-- class绑定 -->
<div id="app-8">
<p :class="[isactive?activeClass:'',errorClass]">噼噼啪啪</p>
</div>
<script>
var app8 = new Vue({
	el:'#app-8',
	data:{
		activeClass:'active',
		errorClass:'error',
		isactive:true
	}
})
</script>

<!-- style绑定 -->
<div id="app-9">
	<p v-bind:style="[fontStyle, fontStyle2]">caca</p>
</div>
<script>
var app9 = new Vue({
	el:'#app-9',
	data:{
		activeColor:'red',
		fontSize:'50px',
		fontStyle:{
			color:'red',
		},
		fontStyle2: {
			'font-size':'30px'
		}
	}
})
</script>

<!-- 条件渲染 -->
<div id="app-10">
	<h1 v-if="ok">yes</h1>
	<h1 v-else>no</h1>
	
	<div v-if="type === 'A'">A:<input type="text" key="kinsert"></div>
    <div v-else-if="type === 'B'">B:<input type="text" key="kinsert"></div>
    <div v-else-if="type === 'C'">C:<input type="text" key="kinsert"></div>
    <div v-else>Z:<input type="text"></div>
    <div v-show="show">v-show</div>
</div>
<script>
	var app10 = new Vue({
		el:'#app-10',
		data:{
			ok:true,
			type:'A',
			show:false
		}
	})
</script>

<!-- 列表渲染 -->
<ul id="app-11">
	<li v-for="(item,index) in items">
	{{set}}--{{index}}-{{item.message}}
	</li>
</ul>
<script>
var app11 = new Vue({
	el:'#app-11',
	data:{
		set:'你大爷',
		items:[
			{message:'111111'} ,
			{message:'222222'}
			]
	}
})
</script>
<ul id="app-12">
	<li v-for="(item,key,index) in items">
	{{index}}--{{key}}-{{item}}
	</li>
</ul>
<script>
var app12 = new Vue({
	el:'#app-12',
	data:{
		items:{
			name:'cccc',
			sex:'111',
			age:12
		}
	}
})
</script>