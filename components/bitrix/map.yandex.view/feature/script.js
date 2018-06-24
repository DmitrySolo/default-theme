if (!window.BX_YMapAddPlacemark)
{
	window.BX_YMapAddPlacemark = function(map, arPlacemark)
	{
		if (null == map)
			return false;

		if(!arPlacemark.LAT || !arPlacemark.LON)
			return false;

		var props = {};
		if (null != arPlacemark.TEXT && arPlacemark.TEXT.length > 0)
		{
			var value_view = '';

			if (arPlacemark.TEXT.length > 0)
			{
				var rnpos = arPlacemark.TEXT.indexOf("\n");
				value_view = rnpos <= 0 ? arPlacemark.TEXT : arPlacemark.TEXT.substring(0, rnpos);
			}

            //props.balloonContent = arPlacemark.TEXT.replace(/\n/g, '<br />');
            //props.hintContent = value_view;

			props.balloonContent = arPlacemark.TEXT.replace(/.+\^/, '');
            var baloonId=arPlacemark.TEXT.replace("^" + props.balloonContent,'');
            //var placemarkName=document.getElementById("placemark-"+baloonId).innerHTML;

			//props.hintContent = $(placemarkName).html();
            //props.iconContent = placemarkName;
            props.baloonId = baloonId;
		}

		var obPlacemark = new ymaps.Placemark(
			[arPlacemark.LAT, arPlacemark.LON],
			props,
			{
                balloonCloseButton: true,
                preset:"twirl#greenStretchyIcon"//,
                //iconImageHref: ''
            }
		);

        obPlacemark.events.add('click',function(ev){
            var baloonId=ev.get('target').properties.get('baloonId');

            //$('ymaps .placemark-custom').removeClass('selected');
            //$('ymaps .placemark-custom[data-id="'+baloonId+'"]').addClass('selected');

            $('.baloon-radio').each(function () {
                if($(this).attr('id') == baloonId) $(this).prop('checked', true);
                else $(this).prop('checked', false);
            })

            //document.getElementById("baloon-content").innerHTML=document.getElementById("baloon-"+baloonId).innerHTML;
        });

		map.geoObjects.add(obPlacemark);

		return obPlacemark;
	}
}

if (!window.BX_YMapAddPolyline)
{
	window.BX_YMapAddPolyline = function(map, arPolyline)
	{
		if (null == map)
			return false;

		if (null != arPolyline.POINTS && arPolyline.POINTS.length > 1)
		{
			var arPoints = [];
			for (var i = 0, len = arPolyline.POINTS.length; i < len; i++)
			{
				arPoints.push([arPolyline.POINTS[i].LAT, arPolyline.POINTS[i].LON]);
			}
		}
		else
		{
			return false;
		}

		var obParams = {clickable: true};
		if (null != arPolyline.STYLE)
		{
			obParams.strokeColor = arPolyline.STYLE.strokeColor;
			obParams.strokeWidth = arPolyline.STYLE.strokeWidth;
		}
		var obPolyline = new ymaps.Polyline(
			arPoints, {balloonContent: arPolyline.TITLE}, obParams
		);

		map.geoObjects.add(obPolyline);

		return obPolyline;
	}
}