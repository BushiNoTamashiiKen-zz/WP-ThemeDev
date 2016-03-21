/*global tinyMCE, tinymce*/
/*jshint forin:true, noarg:true, noempty:true, eqeqeq:true, bitwise:true, strict:true, undef:true, unused:true, curly:true, browser:true, devel:true, maxerr:50 */
(function() {  

    "use strict";
 
    tinymce.PluginManager.add( 'bluthcodes_location', function( editor, url ) {

        editor.addButton( 'bluthcodes', {
            type: 'listbox',
            text: 'Bluthcodes',
            values: [
                {
                    text: 'Alert', 
                    menu: [
                        {
                            text: 'Success',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[alert style="success"]' + tinyMCE.activeEditor.selection.getContent() + '[/alert]');  }
                        },
                        {
                            text: 'Info',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[alert style="info"]' + tinyMCE.activeEditor.selection.getContent() + '[/alert]');  }
                        },
                        {
                            text: 'Warning',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[alert style="warning"]' + tinyMCE.activeEditor.selection.getContent() + '[/alert]');  }
                        },
                        {
                            text: 'Danger',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[alert style="danger"]' + tinyMCE.activeEditor.selection.getContent() + '[/alert]');  }
                        }
                    ]
                },{
                    text: 'Button', 
                    menu: [
                        {
                            text: 'Theme Color',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="theme"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        },
                        {
                            text: 'Default - Grey',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="default"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        },
                        {
                            text: 'Primary - Blue',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="primary"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        },
                        {
                            text: 'Success - Green',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="success"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        },
                        {
                            text: 'Info - Lightblue',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="info"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        },
                        {
                            text: 'Warning - Yellow',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="warning"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        },
                        {
                            text: 'Danger - Red',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[button url="http://" style="danger"]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');  }
                        }
                    ]
                },{
                    text: 'Columns', 
                    menu: [
                        {
                            text: '2 Columns',
                            menu: [
                                {
                                    text: '50% / 50%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[50_50_first]<br /><br />[/50_50_first][50_50_second]<br /><br />[/50_50_second]');  }
                                },
                                {
                                    text: '66% / 33%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[66_33_first]<br /><br />[/66_33_first][66_33_second]<br /><br />[/66_33_second]');  }
                                },
                                {
                                    text: '33% / 66%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[33_66_first]<br /><br />[/33_66_first][33_66_second]<br /><br />[/33_66_second]');  }
                                },
                            ]
                        },
                        {
                            text: '3 Columns',
                            menu: [
                                {
                                    text: '33% / 33% / 33%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[33_33_33_first]<br /><br />[/33_33_33_first][33_33_33_second]<br /><br />[/33_33_33_second][33_33_33_third]<br /><br />[/33_33_33_third]');  }
                                },
                                {
                                    text: '25% / 25% / 50%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[25_25_50_first]<br /><br />[/25_25_50_first][25_25_50_second]<br /><br />[/25_25_50_second][25_25_50_third]<br /><br />[/25_25_50_third]');  }
                                },
                                {
                                    text: '50% / 25% / 25%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[50_25_25_first]<br /><br />[/50_25_25_first][50_25_25_second]<br /><br />[/50_25_25_second][50_25_25_third]<br /><br />[/50_25_25_third]');  }
                                },
                                {
                                    text: '25% / 50% / 25%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[25_50_25_first]<br /><br />[/25_50_25_first][25_50_25_second]<br /><br />[/25_50_25_second][25_50_25_third]<br /><br />[/25_50_25_third]');  }
                                },
                            ]
                        },
                        {
                            text: '4 Columns',
                            menu: [
                                {
                                    text: '25% / 25% / 25% / 25%',
                                    onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[25_25_25_25_first]<br /><br />[/25_25_25_25_first][25_25_25_25_second]<br /><br />[/25_25_25_25_second][25_25_25_25_third]<br /><br />[/25_25_25_25_third][25_25_25_25_fourth]<br /><br />[/25_25_25_25_fourth]');  }
                                },
                            ]
                        },
                    ]   

                },{
                    text: 'Divider', 
                    menu: [
                        {
                            text: 'White',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="white"]');  }
                        },
                        {
                            text: 'Thin',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="thin"]');  }
                        },
                        {
                            text: 'Thick',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="thick"]');  }
                        },
                        {
                            text: 'Short',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="short"]');  }
                        },
                        {
                            text: 'Dotted',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="dotted"]');  }
                        },
                        {
                            text: 'Dashed',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="dashed"]');  }
                        },
                        {
                            text: 'Thin w/big spacing',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[divider type="thin" spacing="25"]');  }
                        },
                    ]
                },{
                    text: 'Google Adsense Ad',
                    menu: [
                        {
                            text: 'Rectangle',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="rectangle" width="300px" align="left"][/adsense]');  }
                        },
                        {
                            text: 'Horizontal',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="horizontal" align="left"][/adsense]');  }
                        },
                        {
                            text: 'Vertical',
                            onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="vertical" align="left"][/adsense]');  }
                        },
                        // {
                        //     text: '300x250 Medium Rectangle',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="300px" height="250px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '180x150 - Small Rectangle',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="180px" height="150px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '336x280 - Large Rectangle',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="336px" height="280px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '250x250 - Square',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="250px" height="250px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '200x200 - Small Square',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="200px" height="200px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '125x125 - Button',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="125px" height="125px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '970x90 - Large Leaderboard',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="970px" height="90px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '728x90 - Leaderboard',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="728px" height="90px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '320x50 - Mobile Leaderboard',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="320px" height="50px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '468x60 - Banner',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="468px" height="60px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '320x100 - Large Mobile Banner',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="320px" height="100px" align="left|right|none"][/adsense]');  }
                        // }, 
                        // {
                        //     text: '300x600 - Half Page',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="300px" height="600px" align="left|right|none"][/adsense]');  }
                        // },
                        // {
                        //     text: '120x600 - Skyscraper',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="120px" height="600px" align="left|right|none"][/adsense]');  }
                        // },  
                        // {
                        //     text: '160x600 - Wide Skyscraper',
                        //     onclick : function(){ tinymce.execCommand('mceInsertContent', false, '[adsense format="auto" width="160px" height="600px" align="left|right|none"][/adsense]');  }
                        // },                    
                        {
                            text: 'Google Ads Size Guide',
                            onclick : function(){ 
                                window.open('https://support.google.com/adsense/answer/6002621?hl=en', '_blank');
                            }
                        },                    
                    ]
                },
            ]
        });
    });
    

})();