# Known issues

## composer asking for input when deploying

Something changed in composer's output making capifony think it wants input (capinfony literally checks for `: ` at the end of lines).
Therefor you need to force `interactive_mode` to be false

## npm install failing

Downgrade node to version 0.10.36. Node did backwards incompatible changes making
npm install fail on execSync.

## generation of fonts failing

* Install the needed dependencies: `brew install batik fontforge ttfautohint ttf2eot`
* Install java development runtime: http://www.oracle.com/technetwork/java/javase/downloads/jdk8-downloads-2133151.html
