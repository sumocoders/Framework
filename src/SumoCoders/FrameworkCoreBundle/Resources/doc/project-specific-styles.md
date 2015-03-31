# Project specific styles

For project specific styles, a default project.scss file is added. It provides
some default (commented) scss for changing the logo.

## Placement of the scss file

The file is placed by default in the FrameworkCoreBundle, since this is the only
required bundle. We advice to create a project specific "AppBundle" in every project
and to put the project specific scss file in there. This way, it'll be easier
to split up the framwork code and your project specific code.
