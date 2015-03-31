class Data
  isInitialized: false
  data: null

  constructor: ->
    @initialize()

  initialize: ->
    throw Error('jsData is not available') if not jsData?
    @data = jsData
    @isInitialized = true
  false

  exists: (key) ->
    @get(key)?

  get: (key) ->
    @initialize() if not @isInitialized

    chunks = key.split '.'

    return @data[key] if chunks.length == 1

    value = @data[chunks[0]];
    chunks.shift()

    while chunks.length != 0
      value = @_getDeeperValue chunks[0], value
      chunks.shift();

    value

  _getDeeperValue: (key, value) ->
    value[key]

Data.current = new Data

window.Data = Data.current
