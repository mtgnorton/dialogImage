let bus = new Vue();

let pagination =  {

  template : '\
  <ul class="pagination" style="float: left;margin: 0">\
  <li><a href="#" @click="getImages(pagination.page-1)">&laquo;</a></li>\
<li v-for="page in allPage" :class="{active:pagination.page==page}"><a @click.prevent="getImages(page)" href="#">{{page}}</a></li>\
 <li><a href="#"  @click="getImages(pagination.page+1)">&raquo;</a></li>\
</ul>\
',
  props: {
    pagination:{
      required : true
    }
  },
  computed:{
    allPage : function () {
      let number =  Math.ceil(this.pagination.allNumber / this.pagination.pageNumber);
      let rs = [];
      for (let i = 1; i<=number; i++){
          rs.push(i);
      }
      return rs;
    }

  },
  methods:{
    getImages : function(page){
      if (page<=this.allPage.length&& page>=1){
        bus.$emit('change-page',page);
      }

    }
  }
};

let manager = new Vue({
  el: '#vue-hook',
  data: {
    allImages:{},
    selectedImages:{},
    pagination:{
      page:1,
      pageNumber:12,
      allNumber:0
    },
    loading:false,
    showbig:false,
    currentImage:'',
    showImagesData:[],
    submitImagePath:''
  },
  mounted:function () {
  this.getImages();
  let _this = this;
  bus.$on('change-page',function (page) {
    _this.pagination.page = page;
    if (_this.allImages[page] === undefined)
    _this.getImages();
  })
  },
  components:{
    'pagination':pagination
  },
  computed:{
    currentImages () {
      let images =  this.allImages[this.pagination.page];
      
      if (images !== undefined)
      return[images.slice(0,6),images.slice(6,12)]
    }
  },

  methods:{
    getImages :function () {
      let self = this;
      this.loading = true;

      this.$nextTick(function () {
        this.postData('/getImages',this.pagination).then(function (data) {

          let newImages = {};

          newImages[self.pagination.page] = data.images;
          self.allImages = Object.assign({},self.allImages,newImages);
          self.pagination.allNumber = data.allNumber;

          data.images.forEach(function (item) {
            let newselectedImages ={};
            newselectedImages[item.path] = false;
            self.selectedImages = Object.assign({},self.selectedImages,newselectedImages);
          })
          self.loading = false;


        })
      })


    },
    postData : (url,data)=>{
    return new Promise(function (resolve,reject) {
      $.ajax(url, {
        type: 'post',
        data: data,
        headers: {
          'X-XSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res){
          resolve(res);
        },
        error: function (error) {
          reject(error);
        }
      });
    })

  },
  chooseImages: function (image,event) {
      this.selectedImages[image.path] = !this.selectedImages[image.path];
      if (event.shiftKey){
        let collection = this.selectedImages;
        let prevSelected = '';
        for (let key in collection){
          if (collection[key] == true && key != image.path)
              prevSelected = key;
          if (key == image.path){
            break;
          }
        }

        let flag = false;
        for(let key in collection){
          if(key == prevSelected)
            flag = true;
          if (flag)
            collection[key] = true;
          if (key == image.path)
            break;
        }

      }

    },

    finish: function () {
       this.showImagesData = this.getSelected();
       this.submitImagePath = Object.values(this.showImagesData).join(',');
       console.log(this.submitImagePath);
       

    },
    getSelected: function () {
      let collection =   this.selectedImages;

      let rs = [];
      for (let key in collection){
        if(collection[key] == true){
          rs.push(key)
        }
      }
      collection  =  this.allImages;
      let result = {};
      for(let key in collection){
        
        collection[key].forEach(function(item){
          if (item.path != undefined && rs.indexOf(item.path) !== -1 )
           result[item.path] = item.url;
        });

      return result;
    }
    },
    showBigMethod : function (item) {
      this.showbig = true;
      this.currentImage = item.url;
    },
    resizeSelected: function() {
      let collection = this.selectedImages;

      for (let key in collection) {
        collection[key] = false;
      }
    },
    deleteImages: function () {
      let _this = this;

      swal({
          title: "确定删除吗？",
          text: "你将无法恢复该文件！",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "确定删除！",
          cancelButtonText: "取消！",
          closeOnConfirm: false
        },
        function(){
          _this.postData('deleteImages',{'images':_this.getSelected}).then(function (data) {
          if (data.status == true){
            swal("删除！", "你的虚拟文件已经被删除。", "success");
            _this.allImages = {};
            _this.selectedImages={};
            _this.pagination = {
              page:1,
              pageNumber:12,
              allNumber:0
            };
            _this.getImages();
            }else{
            sweetAlert("哎呦……", "删除失败！","error");
          }

          });

        });



      }

  }

});