'use strict';
module.exports = function(grunt) {

  grunt.initConfig({

    dirs: {
      js: 'js',
      css: 'css',
      wp_job_manager: 'inc/integrations/wp-job-manager/js',
    },

    watch: {
      options: {
        livereload: 1234,
      },
      js: {
        files: [
          'Gruntfile.js',
          'js/vendor/**/*.js',
          'js/app/*.js',
          'inc/integrations/**/*.coffee',
          'inc/integrations/**/*.js',
          'js/**/*.coffee'
        ],
        tasks: [ 'coffee', 'uglify' ]
      },
      css: {
        files: [
          'css/sass/**/*.scss',
          'css/sass/*.scss'
        ],
        tasks: [ 'sass', 'concat', 'cssmin' ]
      }
    },

    coffee: {
      dist: {
        options: {
          sourceMap: true,
        },
        files: {
          '<%= dirs.wp_job_manager %>/map/app.js': [
            '<%= dirs.wp_job_manager %>/map/app.coffee'
          ],
          '<%= dirs.js %>/widgets/widgets.js': [
            '<%= dirs.js %>/widgets/*.coffee'
          ]
        }
      }
    },

    // uglify to concat, minify, and make source maps
    uglify: {
      dist: {
        files: {
          'inc/integrations/wp-job-manager/js/wp-job-manager.js': [
            'inc/integrations/wp-job-manager/js/source/wp-job-manager.js',
            'inc/integrations/wp-job-manager/js/source/wp-job-manager-apply-with.js'
          ],
          'inc/integrations/wp-job-manager/js/map/app.min.js': [
            'inc/integrations/wp-job-manager/js/map/vendor/**/*.js',
            'inc/integrations/wp-job-manager/js/map/app.js'
          ],
          'js/jobify.min.js': [
            'js/vendor/**/*.js',
            'inc/integrations/wp-job-manager/js/wp-job-manager.js',
            'inc/integrations/woocommerce/js/woocommerce.js',
            'js/widgets/widgets.js',
            'js/app/*.js',
            '!js/vendor/salvattore/*'
          ],
        }
      }
    },

    sass: {
      dist: {
        files: {
          'css/style.css' : 'css/sass/style.scss'
        }
      }
    },

    concat: {
      dist: {
        files: {
          'css/style.css': [ 
						'css/_theme.css', // theme header
						'js/vendor/**/*.css', // js libs
						'css/style.css' // base
					]
        }
      }
    },

    cssmin: {
      dist: {
        files: {
          'style.css': [ 'css/style.css' ]
        }
      }
    },

    cssjanus: {
      theme: {
        options: {
          swapLtrRtlInUrl: false
        },
        files: [
          {
            src: 'style.css',
            dest: 'css/style.min-rtl.css'
          }
        ]
      }
    },

    makepot: {
      theme: {
        options: {
          type: 'wp-theme'
        }
      }
    },

    exec: {
      txpull: {
        cmd: 'tx pull -a --minimum-perc=75'
      },
      txpush_s: {
        cmd: 'tx push -s'
      },
    },

    potomo: {
      dist: {
        options: {
          poDel: false // Set to true if you want to erase the .po
        },
        files: [{
          expand: true,
          cwd: 'languages',
          src: ['*.po'],
          dest: 'languages',
          ext: '.mo',
          nonull: true
        }]
      }
    }

  });

  grunt.loadNpmTasks( 'grunt-contrib-watch' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-coffee' );
  grunt.loadNpmTasks( 'grunt-contrib-concat' );
  grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
  grunt.loadNpmTasks( 'grunt-contrib-sass' );
  grunt.loadNpmTasks( 'grunt-wp-i18n' );
  grunt.loadNpmTasks( 'grunt-cssjanus' );
  grunt.loadNpmTasks( 'grunt-exec' );
  grunt.loadNpmTasks( 'grunt-potomo' );

  // register task
  grunt.registerTask('default', ['watch']);

  grunt.registerTask( 'tx', ['exec:txpull', 'potomo']);
  grunt.registerTask( 'makeandpush', ['makepot', 'exec:txpush_s']);
  grunt.registerTask( 'rtl', ['cssjanus']);

  grunt.registerTask( 'build', [
    'coffee', 'uglify', // JS
    'sass', 'concat', 'cssmin', // CSS
    'makepot', 'tx', 'makeandpush', // l10n, i18n
    'cssjanus', // RTL
  ]);
};
