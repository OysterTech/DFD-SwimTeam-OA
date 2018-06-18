//toEnroll.phpwxLogin.php

wx.getStorage({
	key:'openID',
	success:function(res){
		openID=res.data;
	}
})

//��ȡ���б���
wx.request({
	url:API_HOST+'getAllGames.php',
	header:{
		'content-type':'application/json'
	},
	success:function(res){
		console.log(res.data);
		
	}
})

//��ȡ��������Ŀ
wx.request({
	url:API_HOST+'getGamesEnrollItem.php',
	type:"POST",
	data:{
		AthID:_this.AthID,
		GamesID:_this.GamesID
	},
	header:{
		'content-type':'application/json'
	},
	success:function(res){
		console.log(res.data);
	}
})

//��ȡ������Ŀ
wx.request({
	url:API_HOST+'getGamesItem.php',
	data:{
		GamesID:_this.GamesID
	},
	header:{
		'content-type':'application/json'
	},
	success:function(res){
		console.log(res.data);
	}
})

//��ȡ��������
wx.request({
	url:API_HOST+'getProfile.php',
	type:"POST",
	data:{
		openID:_this.openID
	},
	header:{
		'content-type':'application/json'
	},
	success:function(res){
		console.log(res.data);
	}
})