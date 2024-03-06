// gulpfile.js
const gulp = require('gulp');
const sass = require('gulp-sass');

// Завдання для компіляції SCSS в CSS
gulp.task('sass', function () {
  return gulp.src('src/scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('dist/css'));
});

// Завдання для спостереження за змінами в SCSS
gulp.task('watch', function () {
  gulp.watch('src/scss/**/*.scss', gulp.series('sass'));
});

// Завдання за замовчуванням
gulp.task('default', gulp.series('sass', 'watch'));
