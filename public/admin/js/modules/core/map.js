define(function(require, exports, module) {
    var t = function(t, a) {
        //地图
        var map = null;
        var searchService = null;
        var serchType = 2;
        var lat = Number($('input[name="data[lat]"]').val() || 39.916527);
        var lng = Number($('input[name="data[lng]"]').val() || 116.397128);
        var init = function() {
            var myLatlng = new qq.maps.LatLng(lat, lng);
            var myOptions = {
                zoom: 14,
                center: myLatlng,
                disableDoubleClickZoom: true,
                draggable: true,
                scaleControl: true,
                panControl: true,
                scrollwheel: true,
                keyboardShortcuts: true,
                mapTypeControl: true,
                zoomControl: true,
            };
            map = new qq.maps.Map(document.getElementById("container"), myOptions);
            var centerPoint = '<div id ="centerPoint"><img src="/images/map.png" alt="" /><div>'
            $('#container').append(centerPoint)

            //当地图中心属性更改时触发事件
            qq.maps.event.addListener(map, 'center_changed', function() {
                if (serchType == 1) {
                    changeLat(map.getCenter())
                }
            });
            qq.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(new qq.maps.LatLng(event.latLng.getLat(), event.latLng.getLng()));
                changeLat({ 'lat': event.latLng.getLat(), 'lng': event.latLng.getLng() });
            });
            //调用Poi检索类
            searchService = new qq.maps.SearchService({
                panel: document.getElementById('infoDiv'),
                //检索成功的回调函数
                complete: function(results) {
                    if (!results.detail.pois) {
                        document.getElementById('#infoDiv').hide();
                        message.error('搜索结果为空，请选择标记新地址');
                        return
                    }
                    $(document).off('click').on('click', '#infoDiv li', function() {
                        console.log(this);
                        var num = $(this).index();
                        changeLat(results.detail.pois[num].latLng)
                    })
                },
                map: map
            });
            //编辑显示定位图标
            $(function() {
                if ($('input[name="data[lng]"]').val() && $('input[name="data[lng]"]').val()) {
                    $('#centerPoint').show();
                }
            })
        }
        init();

        function getResult() {
            if ($('.province').val() == '' || $('.city').val() == '' || $('.district').val() == '') {
                message.error('请选择省市区');
                return;
            }
            serchType = 2
            var poiText = $("#poiText").val();
            if (poiText.length == 0) {
                message.error('请输入地址信息');
                return;
            }
            $('#infoDiv').show();
            $('#centerPoint').hide();
            var regionText = $("#regionText option:selected").text().trim();
            searchService.setLocation(regionText);
            searchService.search(poiText);
            searchService.setPageCapacity(4);
            searchService.setError(function() {
                message.error('搜索结果为空，请选择标记新地址');
            })
        }
        $('#seach').on('click', function() {
            getResult()
        })

        function changeLat(latlng) {
            var lat = latlng.lat;
            var lng = latlng.lng;
            $('input[name="data[lng]"]').attr('value', lng)
            $('input[name="data[lat]"]').attr('value', lat)
        }
        $("#changeSearchType").on('click', function() {
            changeType()
        })

        function changeType(events) {
            serchType = 1
            $('#infoDiv').hide();
            $('#centerPoint').show();
            searchService.clear()
        }
    }
    exports.bootstrap = function(e, i) {
        $(t(e, i))
    }

});
