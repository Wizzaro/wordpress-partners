module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        //----------------------------------------
        compass: {
            front_slider: {
                options: {            
                    config: 'assets-dev/front/sass/slider/config.rb',
                    specify: [
                        'assets-dev/front/sass/slider/slider.scss',
                    ],
                    outputStyle: 'compressed',
                    environment: 'production'
                }
            },   
            front_list: {
                options: {            
                    config: 'assets-dev/front/sass/list/config.rb',
                    specify: [
                        'assets-dev/front/sass/list/list.scss',
                    ],
                    outputStyle: 'compressed',
                    environment: 'production'
                }
            },              
            admin_metabox_elements: {                   
                options: {            
                    config: 'assets-dev/admin/sass/metabox-elements/config.rb',
                    specify: [
                        'assets-dev/admin/sass/metabox-elements/metabox-elements.scss',
                    ],
                    outputStyle: 'compressed',
                    environment: 'production'
                }
            },
            admin_metabox_partner_data: {                   
                options: {            
                    config: 'assets-dev/admin/sass/metabox-partner-data/config.rb',
                    specify: [
                        'assets-dev/admin/sass/metabox-partner-data/metabox-partner-data.scss',
                    ],
                    outputStyle: 'compressed',
                    environment: 'production'
                }
            }
        },
        //----------------------------------------
        concat: {
            front_slider: {
                src: [
                    'assets-dev/front/js/slider/slider.js',
                    'assets-dev/front/js/slider/*.js',
                    'assets-dev/front/js/slider/*/*.js',
                    'assets-dev/front/js/slider/*/*/*.js'
                ],
                dest: 'assets/js/slider.js'  
            },
            admin_metabox_elements: {
                src: [
                    'assets-dev/admin/js/metabox-elements/main.js',
                    'assets-dev/admin/js/metabox-elements/config/*.js',
                    'assets-dev/admin/js/metabox-elements/entity/*.js',
                    'assets-dev/admin/js/metabox-elements/collections/*.js',
                    'assets-dev/admin/js/metabox-elements/view/*.js'
                    
                ],
                dest: 'assets/js/admin/metabox-elements.js'
            },
            admin_metabox_partner_data: {
                src: [
                    'assets-dev/admin/js/metabox-partner-data/main.js',
                    
                ],
                dest: 'assets/js/admin/metabox-partner-data.js'
            }
        },
        //----------------------------------------
        uglify: {
            front_slider: {
                files: {
                    'assets/js/slider.js': [
                        'assets-dev/front/js/slider/slider.js',
                        'assets-dev/front/js/slider/*.js',
                        'assets-dev/front/js/slider/*/*.js',
                        'assets-dev/front/js/slider/*/*/*.js'
                    ],
                    
                },
            },
            admin_metabox_elements: {
                files: {
                    'assets/js/admin/metabox-elements.js': [
                        'assets-dev/admin/js/metabox-elements/main.js',
                        'assets-dev/admin/js/metabox-elements/config/*.js',
                        'assets-dev/admin/js/metabox-elements/entity/*.js',
                        'assets-dev/admin/js/metabox-elements/collections/*.js',
                        'assets-dev/admin/js/metabox-elements/view/*.js'
                    ],
                    
                },
            },
            admin_metabox_partner_data: {
                files: {
                    'assets/js/admin/metabox-partner-data.js': [
                        'assets-dev/admin/js/metabox-partner-data/main.js',
                    ],
                    
                },
            }
        },
        //----------------------------------------
        watch: {
            front_slider_css: {
                files: [
                    'assets-dev/front/sass/slider/slider.scss',
                    'assets-dev/front/sass/slider/*.scss',
                    'assets-dev/front/sass/slider/*/*.scss',
                    'assets-dev/front/sass/slider/*/*/*.scss'
                ],
                tasks: ['compass:front_slider']
            },
            front_slider_js: {
                files: [
                    'assets-dev/front/js/slider/slider.js',
                    'assets-dev/front/js/slider/*.js',
                    'assets-dev/front/js/slider/*/*.js',
                    'assets-dev/front/js/slider/*/*/*.js'
                ],
                tasks: ['concat:front_slider']
            },
            front_list_css: {
                files: [
                    'assets-dev/front/sass/list/list.scss',
                    'assets-dev/front/sass/list/*.scss',
                    'assets-dev/front/sass/list/*/*.scss',
                    'assets-dev/front/sass/list/*/*/*.scss'
                ],
                tasks: ['compass:front_list']
            },
            admin_css_metabox_elements: {
                files: [
                    'assets-dev/admin/sass/metabox-elements/metabox-elements.scss',
                    'assets-dev/admin/sass/metabox-elements/*.scss',
                    'assets-dev/admin/sass/metabox-elements/*/*.scss',
                    'assets-dev/admin/sass/metabox-elements/*/*/*.scss',
                ],
                tasks: ['compass:admin_metabox_elements']
            },
            admin_js_metabox_elements: {
                files: [
                    'assets-dev/admin/js/metabox-elements/*.js',
                    'assets-dev/admin/js/metabox-elements/*/*.js',
                    'assets-dev/admin/js/metabox-elements/*/*/*.js'
                ],
                tasks: ['concat:admin_metabox_elements']
            },
            admin_css_metabox_partner_data: {
                files: [
                    'assets-dev/admin/sass/metabox-partner-data/metabox-partner-data.scss',
                    'assets-dev/admin/sass/metabox-partner-data/*.scss',
                    'assets-dev/admin/sass/metabox-partner-data/*/*.scss',
                    'assets-dev/admin/sass/metabox-partner-data/*/*/*.scss',
                ],
                tasks: ['compass:admin_metabox_partner_data']
            },
            admin_js_metabox_partner_data: {
                files: [
                    'assets-dev/admin/js/metabox-partner-data/main.js',
                ],
                tasks: ['concat:admin_metabox_partner_data']
            },
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('default', ['compass', 'uglify']);
    grunt.registerTask('liveupdate', ['watch']);
    
    grunt.registerTask('liveupdate_admin_metabox_elements_css', ['watch:admin_css_metabox_elements']);
    grunt.registerTask('liveupdate_admin_metabox_elements_js', ['watch:admin_js_metabox_elements']);
    
    grunt.registerTask('liveupdate_admin_metabox_partner_data_css', ['watch:admin_css_metabox_partner_data']);
    grunt.registerTask('liveupdate_admin_metabox_partner_data_js', ['watch:admin_js_metabox_partner_data']);
    
    grunt.registerTask('liveupdate_front_slider_css', ['watch:front_slider_css']);
    grunt.registerTask('liveupdate_front_slider_js', ['watch:front_slider_js']);
    
    grunt.registerTask('liveupdate_front_list_css', ['watch:front_list_css']);
};
