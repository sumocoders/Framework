var exec = require('child_process').exec;
var through = require('through2');
var gutil = require('gulp-util');

var PluginError = gutil.PluginError;

module.exports = function(action) {
  function parse(file, cb) {
    var cmd = 'cat ' + file + ' | grep script -C1 | grep asset -C1';

    var inputFiles = [];
    var grouped = [];
    var fullGroup = started = false;

    exec(cmd, function(error, stdout, stderr) {
      // group which files should be concatted to which files
      var lines = stdout.split('\n');
      lines.forEach(function(line) {
        // check if an environment check starts here
        if (line.indexOf('app.environment') > -1) {
          started = true;
          return;
        }

        if (!started) {
          return;
        }

        // find the url and add it as destination or source script file
        if (line.indexOf('asset(\'') > -1) {
          var url = /asset\('([^']+)'\)/g.exec(line)[1];
          if (fullGroup) {
            // we already have collected the dev files. Fetch the production file
            grouped[url] = inputFiles;
            inputFiles = [];
            fullGroup = started = false;
            return;
          }

          // add a file
          inputFiles.push('./web' + url);
            return;
          }

        // if we encounter an else statement, we'll get the prod file next
        if (line.indexOf('else') > -1) {
          fullGroup = true;
        }
      });

      action(grouped);
      cb(null, file);
    });
  }

  return through.obj(function(file, enc, cb) {
    if (file.isNull()) return cb(null, file);
    if (file.isStream()) return cb(new PluginError('parse-twig', 'Streaming is not supported'));

    console.log(file.path);

    parse(file.path, cb);
  });
};
