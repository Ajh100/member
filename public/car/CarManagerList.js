/*生成车源列表*/
function ContentList(flag) {
    if (flag == 0) {
        if (curIndex > rowNum) {
            return false;
        }
    }
    var data = "dealerid=" + dealerId + "&sindex=" + curIndex + "&status=" + Cmd.GetUrlParam('s') + "&bid=" + bid + "&sid=" + sid + "&specid=" + specid + "&saleid=" + saleid + "&overtime=" + overtime + "&order=" + order + "&key=" + key + "&categoryid=" + radioId;
    
    $.get("/Handler/CarManager/GetCarList.ashx", data, function (ret) {
        if (ret != null && ret != "") {
            islist = true;
            $("input[name='txtSearch']").val('');
            var arrRet = ret.split('#$#');
            rowNum = parseInt(arrRet[0]);
            $(".fontb").text(rowNum);
            if (rowNum == 0 && curIndex == 1) {
                $(".manage-list .manage-no").remove();
                $(".manage-list ul:eq(1)").html("");
                $(".manage-list").append("<div class=\"manage-no\">暂无车源</div>");
                $("#divFooter").css("display", "");
            }
            else {
                curIndex = curIndex + pageSize;
                $(".manage-list .manage-no").remove();
                if (flag == 0)
                    $(".manage-list ul:eq(1)").append(arrRet[1]);
                else
                    $(".manage-list ul:eq(1)").html(arrRet[1]);
                BindHover();
                
                if (curIndex >= rowNum) {
                    $("#divFooter").css("display", "");
                }
                else {
                    $("#divFooter").css("display", "none");
                }
            }
        }
    })


}
/*鼠标滑过*/
function BindHover() {
    $(".manage-list-ul li").hover(function () {
        clearTimeout(timer);
        var liObj = $(this);
        timer = setTimeout(function () {
            liObj.attr("class", "change-color");            
        },500);
        
    }, function () {
        clearTimeout(timer);
        $(this).removeClass();      

    });
}


/*排序*/
function ChangeOrder(obj) {
    var className = $(".time em")[0].className;
    var rowNum = parseInt($(".fontb").text());
    if (className == "down") {
        $(".time em")[0].className = "";
        order = 2;
        curIndex = 1;
        if (rowNum > 0) {
            ContentList(1);
        }
    }
    else {
        $(".time em")[0].className = "down";
        order = 1;
        curIndex = 1;
        if (rowNum > 0) {
            ContentList(1);
        }
    }
}
/*已售*/
function SelledType(type)
{
    var obj = $(".result li");

    for (var i = 0; i < 3; i++)
    {
        if (i == type) {
            obj[i].className = "current";
        }
        else {
            obj.eq(i).removeClass();
        }
    }
    selled = type;
    bid = 0, sid = 0, specid = 0, saleid = 0, key = "", order = 2;
    curIndex = 1;
    ContentList(1);
}
