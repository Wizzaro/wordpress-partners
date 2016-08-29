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
            admin_metabox_elements: {                   
                options: {            
                    config: 'assets-dev/admin/sass/metabox-elements/config.rb',
                    specify: [
                        'assets-dev/admin/sass/metabox-elements/metabox-elements.scss',
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
    
    grunt.registerTask('liveupdate_front_slider_css', ['watch:front_slider_css']);
    grunt.registerTask('liveupdate_front_slider_js', ['watch:front_slider_js']);
};
