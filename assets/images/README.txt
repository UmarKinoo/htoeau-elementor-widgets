Optional default images for Elementor MEDIA controls
=====================================================

Place files here and reference them from widget PHP using:

  \HtoEAU_Widgets\default_bundle_image_url( 'your-file.png' );

Example: hero-default.png is used automatically by the Hero widget when present.

These URLs are stable across sites (relative to the plugin folder), so they work
after zipping and installing the plugin elsewhere. WordPress Media Library IDs
in exported templates will NOT transfer to a new site unless you also export
media or use URLs like these.

Suggested exports from your design handoff (match Figma / existing site):
  - hero-default.png
  - (add others as you wire defaults in each widget)

Do not commit huge binaries to git if you use Git LFS or ship them separately.
