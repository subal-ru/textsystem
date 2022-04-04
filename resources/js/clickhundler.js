// import Vue from 'vue';
clickhundler = {
    copytext: function() {
        $copytext = document.getElementsByClassName('copytext');
        $copytext[0].select();
        document.execCommand("copy");
    }
};
