/* jshint node:true */
module.exports = function( grunt ) {
	'use strict';

	grunt.initConfig( {

		// gets the package vars
		pkg: grunt.file.readJSON( 'package.json' ),
		svn_settings: {
			path: '../../../../wp_plugins/<%= pkg.name %>',
			tag: '<%= svn_settings.path %>/tags/<%= pkg.version %>',
			trunk: '<%= svn_settings.path %>/trunk',
			exclude: [
				'.git/',
				'.sass-cache/',
				'node_modules/',
				'admin/assets/js/admin.js',
				'public/assets/sass/',
				'public/assets/images/sprite.svg',
				'.editorconfig',
				'.gitignore',
				'.jshintrc',
				'Gruntfile.js',
				'README.md',
				'package.json',
				'*.zip'
			]
		},

		// javascript linting with jshint
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'Gruntfile.js',
				'admin/assets/js/admin.js'
			]
		},

		// uglify to concat and minify
		uglify: {
			dist: {
				files: {
					'admin/assets/js/admin.min.js': ['admin/assets/js/admin.js']
				}
			}
		},

		// compass and scss
		compass: {
			dist: {
				options: {
					httpPath: '',
					sassDir: 'public/assets/sass',
					cssDir: 'public/assets/css',
					imagesDir: 'public/assets/images',
					javascriptsDir: 'public/assets/js',
					fontsDir: 'public/assets/fonts',
					environment: 'production',
					relativeAssets: true,
					noLineComments: true,
					outputStyle: 'compressed'
				}
			}
		},

		// watch for changes and trigger compass
		watch: {
			compass: {
				files: [
					'public/assets/sass/**'
				],
				tasks: ['compass']
			},
			js: {
				files: [
					'<%= jshint.all %>'
				],
				tasks: ['jshint', 'uglify']
			}
		},

		// rsync commands used to take the files to svn repository
		rsync: {
			options: {
				args: ['--verbose'],
				exclude: '<%= svn_settings.exclude %>',
				recursive: true
			},
			tag: {
				options: {
					src: './',
					dest: '<%= svn_settings.tag %>'
				}
			},
			trunk: {
				options: {
					src: './',
					dest: '<%= svn_settings.trunk %>'
				}
			}
		},

		// shell command to commit the new version of the plugin
		shell: {
			svn_add: {
				command: 'svn add --force * --auto-props --parents --depth infinity -q',
				options: {
					stdout: true,
					stderr: true,
					execOptions: {
						cwd: '<%= svn_settings.path %>'
					}
				}
			},
			svn_commit: {
				command: 'svn commit -m "updated the plugin version to <%= pkg.version %>"',
				options: {
					stdout: true,
					stderr: true,
					execOptions: {
						cwd: '<%= svn_settings.path %>'
					}
				}
			}
		}
	});

	// load tasks
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-compass' );
	grunt.loadNpmTasks( 'grunt-rsync' );
	grunt.loadNpmTasks( 'grunt-shell' );

	// default task
	grunt.registerTask( 'default', [
		'jshint',
		'compass',
		'uglify'
	] );

	// deploy task
	grunt.registerTask( 'deploy', [
		'default',
		'rsync:tag',
		'rsync:trunk',
		'shell:svn_add',
		'shell:svn_commit'
	] );
};
