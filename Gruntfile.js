module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        //----------------------------------------
        compass: {                  
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
            js_kubusiowakraina_theme: {
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
};
