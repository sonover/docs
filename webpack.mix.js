const mix = require("laravel-mix");

mix
  .setPublicPath("public")
  .postCss("resources/css/documentation.css", "public/css", [
    require("postcss-easy-import")(),
    require("tailwindcss"),
  ]);

if (mix.inProduction()) {
  mix.version();
}
