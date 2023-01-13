function PagingNavigation(idContainerParam) {

    this.idContainer = '#containerPaging';
    var countPaging = $(this.idContainer).find(".page").length;
    var classPagingName = "paging";
    var i = 1;
    var currentPage = 1;
    var containerBtnPaging = '<div class="btnPage" style="clear:both"></div>';
    var BtnPrevious = '<button class="btn btn-success btnPrevious" style="float:left"><i class="fa fa-caret-left"></i> Sebelumnya</button>';
    var BtnNext = '<button class="btn btn-success btnNext" style="float:right">Selanjutnya <i class="fa fa-caret-right"></i></button>';
    var BtnFinish = '<button class="btn btn-success btnFinish" style="float:right">Submit <i class="fa fa-check"></i></button>';
    var functionBtnFinish = '';
    /*
     * Can access this.method
     * inside other methods using
     * root.method()
     */
    var root = this;

    /*
     * Constructor
     */
    this.construct = function (idContainerParam) {
        idContainer = idContainerParam;
        countPaging = $(idContainer).find(".page").length;

        $(idContainer).append(containerBtnPaging);
        $(idContainer + " .btnPage").html(BtnNext);

        $(idContainer + " .page").each(function () {
            if (i != 1) $(this).css("display", "none");
            $(this).addClass(classPagingName + i);

            i++;
        });

        this.setFuncFinish = function(func){
            functionBtnFinish = func;
        };

        InitbtnNext();


    };


    function InitBtnPrevious() {
        $(idContainer + " .btnPrevious").on("click", function () {

            currentPage--;
            var previousPage = currentPage + 1;
            if (currentPage == 1) {
                $(idContainer + " .btnPage").html(BtnNext);
            } else {
                $(idContainer + " .btnPage").html(BtnPrevious + BtnNext);
            }
            InitbtnNext();
            InitBtnPrevious();


            $(idContainer + " ." + classPagingName + currentPage).css("display", "inline");
            $(idContainer + " ." + classPagingName + previousPage).css("display", "none");
            // console.log(currentPage);
        });
    }

    function InitbtnNext() {
        $(idContainer + " .btnNext").on("click", function () {
            currentPage++;
            var previousPage = currentPage - 1;
            if (currentPage == countPaging) {
                $(idContainer + " .btnPage").html(BtnPrevious + BtnFinish);
            } else {
                $(idContainer + " .btnPage").html(BtnPrevious + BtnNext);
            }
            InitbtnNext();
            InitBtnPrevious();
            initBtnFInish();

            $(idContainer + " ." + classPagingName + currentPage).css("display", "inline");
            $(idContainer + " ." + classPagingName + previousPage).css("display", "none");
        });
    };

    function initBtnFInish (){
        $(".btnFinish").on("click", functionBtnFinish);
    };

    /*
     * Pass options when class instantiated
     */
    this.construct(idContainerParam);

};