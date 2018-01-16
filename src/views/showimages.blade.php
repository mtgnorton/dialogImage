{!! Mtg\ShowImage\ShowImagesService::css() !!}

<style>
        .modal-dialog{
            width:auto;
            height: auto;
        }
        .img-wrapper{

            position: relative;
            margin: 10px 0;
            width: 180px;
            height: 150px;
            padding: 0;
        }
        .img-operation{
            position: absolute;
            width: 150px;
            bottom:0;
            left:0;
        }
        .img-operation i{
            float: right;
            margin-right: 10px;
        }
        .img-wrapper>img{
            width: 180px;
            height: 150px;
        }
        .imgchoose{
            padding:4px;
            border: 1px solid #337ab7;
        }
        .loading{
            width:1217px;
            height: 340px;
            display:flex;
            justify-content: center;
            align-items: center;
        }

        .img-big-wrapper{
            height:600px;
            width: 1217px;
            display: flex;
            justify-content: center;
            align-items: center

        }
        .img-big-wrapper>img{
            max-width: 1217px; max-height: 600px; vertical-align: middle;
        }

    </style>

<div class="form-group">

    <label class="col-sm-2 control-label">选择图片</label>
    <div class="col-sm-8">

<div id="vue-hook">
        <button class="btn btn-primary btn-lg" type="button" data-toggle="modal" data-target="#show-img">
            线上图片
        </button>
        <div class="row" >
            <div class="col-sm-6 col-md-2" v-if="showImagesData" v-for="image in showImagesData" style="max-width: 200px">
                <a href="#" class="thumbnail" >
                    <img :src="image"
                         alt="通用的占位符缩略图">
                </a>
            </div>
        </div>

  <input type="hidden" name="selected-images" :value="submitImagePath">
<div class="modal fade" id="show-img" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true" data-show="true">
    <div class="modal-dialog">
        <div class="modal-content  col-md-8 col-md-offset-2" id="">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    图片列表
                </h4>
            </div>

            <div class="modal-body" style="min-height:370px">


                <div class="row" v-for="collection in currentImages" v-show="currentImages.length != 0 && !loading && !showbig" >
                    <div class="col-md-2" v-for="item in collection" >
                        <div class="img-wrapper"  @click="chooseImages(item,$event)" @dblclick="showBigMethod(item)">
                        <img :src="item.url" alt="" class="img-rounded " :class="{'imgchoose':selectedImages[item.path]}">
                        <div class="img-operation">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"  v-show="showbig">
                <div class="col-md-12  img-big-wrapper"  @dblclick="showbig=false;" >
                <img :src="currentImage">
            </div>
        </div>


        <div class="row" v-show="loading">
            <div class="col-md-12">
                <div  class="loading" >
                    正在加载中...
                </div>
            </div>
        </div>

    </div>


    <div class="modal-footer">
        <pagination :pagination="pagination"></pagination>

        <button type="button" class="btn btn-default" data-dismiss="modal">关闭
        </button>
        <button type="button" class="btn btn-danger"  @click="deleteImages()">删除
        </button>
        <button type="button" class="btn btn-info" @click="resizeSelected">重置选择
        </button>
        <button type="button" class="btn btn-primary" data-dismiss="modal"  @click="finish">
        完成
        </button>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal -->

</div>

</div>
</div>
</div>
        {!! Mtg\ShowImage\ShowImagesService::js() !!}
